<?php

$stores = [];

$currencies = [
    'currencyIsoCode' => [
        'NO' => 'NOK',
        'FI' => 'EUR',
        'DK' => 'DKK',
        'SE' => 'SEK',
        ],
    'currencyIsoCodes' => [
        'NO' => ['NOK'],
        'FI' => ['EUR'],
        'DK' => ['DKK'],
        'SE' => ['SEK'],
    ],
];

$countriesPerStore = [];

if (!empty(getenv('SPRYKER_ACTIVE_STORES'))) {
    $activeStores = array_map('trim', explode(',', getenv('SPRYKER_ACTIVE_STORES')));

    $template = [
        // different contexts
        'contexts' => [
            // shared settings for all contexts
            '*' => [
                'timezone' => 'Europe/Berlin',
                'dateFormat' => [
                    // short date (01.02.12)
                    'short' => 'd/m/Y',
                    // medium Date (01. Feb 2012)
                    'medium' => 'd. M Y',
                    // date formatted as described in RFC 2822
                    'rfc' => 'r',
                    'datetime' => 'Y-m-d H:i:s',
                ],
            ],
            // settings for contexts (overwrite shared)
            'yves' => [],
            'zed' => [
                'dateFormat' => [
                    // short date (2012-12-28)
                    'short' => 'Y-m-d',
                ],
            ],
        ],
        'locales' => [
            // first entry is default
            'en' => 'en_US',
            'nn' => 'nn_NO',
            'fi' => 'fi_FI',
            'da' => 'da_DK',
            'sv' => 'sv_SE',
        ],

        'queuePools' => [
            'synchronizationPool' => [],
        ],
        'storesWithSharedPersistence' => [],
    ];

    foreach ($activeStores as $store) {
        $template['countries'] = $countriesPerStore[$store] ?? [$store];
        $template['currencyIsoCode'] = $currencies['currencyIsoCode'][$store] ?? 'EUR';
        $template['currencyIsoCodes'] = $currencies['currencyIsoCodes'][$store] ?? ['EUR'];
        $stores[$store] = $template;
        $stores[$store]['storesWithSharedPersistence'] = array_diff($activeStores, [$store]);
        $stores[$store]['queuePools']['synchronizationPool'] = array_map(
            static function ($store) {
                return $store . '-connection';
            },
            $activeStores
        );
    }

    return $stores;
}

return $stores;
