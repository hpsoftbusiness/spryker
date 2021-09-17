<?php

$stores = [];

$currencies = [
    'currencyIsoCode' => [
        'BG' => 'BGN',
        'MK' => 'MKD',
        'AL' => 'ALL',
        'XK' => 'EUR',
        ],
    'currencyIsoCodes' => [
        'BG' => ['BGN'],
        'MK' => ['MKD'],
        'AL' => ['ALL'],
        'XK' => ['EUR'],
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
            'en_US',
            'bg_BG',
            'mk_MK',
            'sq_AL',
            'sr_RS',
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
