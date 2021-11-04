<?php
// config file for overriding by cluster


use Pyz\Shared\Adyen\AdyenConstants;
use Pyz\Shared\Country\CountryConstants;
use Pyz\Shared\Locale\LocaleConstants;
use Pyz\Shared\MyWorldMarketplaceApi\MyWorldMarketplaceApiConstants;
use SprykerEco\Shared\AdyenApi\AdyenApiConstants;

$config[CountryConstants::CLUSTER_COUNTRIES] = ['PL', 'SK', 'CZ'];

$config[LocaleConstants::COUNTRY_TO_LOCALE_RELATIONS] = [
    'PL' => 'pl_PL',
    'SK' => 'sk_SK',
    'CZ' => 'cs_CZ',
];

// TODO:: create ORIGIN KEYS for prod environment
$adyenCredentials = [
    'ORIGIN_KEYS' => [
        'PL' => getenv('ADYEN_ORIGIN_KEYS_PL'),
        'SK' => getenv('ADYEN_ORIGIN_KEYS_SK'),
        'CZ' => getenv('ADYEN_ORIGIN_KEYS_CZ'),
    ],
    'API_KEYS' => [
        'PL' => getenv('ADYEN_API_KEY_PL'),
        'SK' => getenv('ADYEN_API_KEY_SK'),
        'CZ' => getenv('ADYEN_API_KEY_CZ'),
    ],
    'MERCHANT_ACCOUNTS' => [
        'PL' => 'MyWorldPolandSpzoo',
        'SK' => 'MyWorldSlovakiaSro',
        'CZ' => 'MyWorldsro',
    ],
];

$config[AdyenApiConstants::API_KEY] = $adyenCredentials['API_KEYS'][APPLICATION_STORE];
$config[AdyenConstants::MERCHANT_ACCOUNT] = $adyenCredentials['MERCHANT_ACCOUNTS'][APPLICATION_STORE];
$config[AdyenConstants::SDK_CHECKOUT_ORIGIN_KEY] = $adyenCredentials['ORIGIN_KEYS'][APPLICATION_STORE];

$adyenSplitAccounts = [
    'PL' => '180783198',
    'SK' => '121195668',
    'CZ' => '134217451',
];

$config[AdyenConstants::SPLIT_ACCOUNT] = $adyenSplitAccounts[APPLICATION_STORE];
$config[AdyenConstants::SPLIT_ACCOUNT_COMMISSION_INTEREST] = 0.05;

$defaultDealerId = [
    'PL' => '681aca46-da57-4337-8acc-adc6008db275',
    'SK' => '338856FD-4D6C-40B5-8117-AAB0008676D1',
    'CZ' => '77d0eca3-52d4-4102-8933-adc700c64393',
];

$config[MyWorldMarketplaceApiConstants::DEALER_ID_DEFAULT] = $defaultDealerId[APPLICATION_STORE];
