<?php

$stores = [];

if (!empty(getenv('SPRYKER_ACTIVE_STORES')) && !empty(getenv('SPRYKER_CLUSTER'))) {
    $activeStores = array_map('trim', explode(',', getenv('SPRYKER_ACTIVE_STORES')));

    return require('clusters/' . getenv('SPRYKER_CLUSTER') . '/stores.php');
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
    'countries' => ['AT', 'BE', 'BG', 'HR', 'CY', 'CZ', 'DK', 'EE', 'FI', 'FR', 'DE', 'GR', 'HU', 'IE', 'IM', 'IT', 'LV', 'LT', 'LU', 'MT', 'MC', 'NL', 'NO', 'PL', 'PT', 'RO', 'SK', 'SI', 'ES', 'SE', 'CH', 'GB'],

    // internal and shop
    'currencyIsoCode' => 'EUR',
    'currencyIsoCodes' => ['EUR'],
    'queuePools' => [
        'synchronizationPool' => [
            'DE-connection',
        ],
    ],
    'storesWithSharedPersistence' => [],
];

return $stores;
