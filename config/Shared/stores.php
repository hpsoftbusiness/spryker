<?php
$stores = [];

$countries = [
    'AT',
    'BE',
    'BG',
    'HR',
    'CH',
    'CY',
    'CZ',
    'DK',
    'EE',
    'FI',
    'FR',
    'DE',
    'GB',
    'GR',
    'HU',
    'IE',
    'IM',
    'IT',
    'LV',
    'LT',
    'LU',
    'MT',
    'MC',
    'NO',
    'NL',
    'PL',
    'PT',
    'RO',
    'SE',
    'SI',
    'SK',
    'ES',
    'UK',
    'US',
];

$currencies = [
    'EUR',
    'PLN',
    'CZK',
    'SEK',
    'HUF',
    'CHF',
    'NOK',
    'GBP',
    'DKK',
    'LEU',
    'USD',
];

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
            'de' => 'de_DE',
//            'pl' => 'pl_PL',
//            'it' => 'it_IT',
        ],
        // first entry is default
        'countries' => $countries,
        // internal and shop
        'currencyIsoCode' => 'EUR',
        'currencyIsoCodes' => $currencies,
        'queuePools' => [
            'synchronizationPool' => [],
        ],
        'storesWithSharedPersistence' => [],
    ];
    foreach ($activeStores as $store) {
        $stores[$store] = $template;
        $stores[$store]['storesWithSharedPersistence'] = array_diff($activeStores, [$store]);
        $stores[$store]['queuePools']['synchronizationPool'] = array_map(static function ($store) {
            return $store . '-connection';
        }, $activeStores);
    }

    return $stores;
}
$stores['DE'] = [
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
        'de' => 'de_DE',
//        'pl' => 'pl_PL',
//        'it' => 'it_IT',
    ],
    // first entry is default
    'countries' => $countries,
    // internal and shop
    'currencyIsoCode' => 'EUR',
    'currencyIsoCodes' => $currencies,
    'queuePools' => [
        'synchronizationPool' => [
            'DE-connection',
        ],
    ],
    'storesWithSharedPersistence' => [],
];

return $stores;
