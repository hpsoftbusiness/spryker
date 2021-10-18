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
        'PL' => getenv('ADYEN_ORIGIN_KEYS_PL') ?: utf8_encode('pub.v2.1116342036411490.aHR0cHM6Ly93d3cucGwucHJvZHVjdHMubXl3b3JsZC5jb20.wSi9XtNFC2US30uCZQqDilIE4bFju3Ax0QH1BflEGro'),
        'SK' => getenv('ADYEN_ORIGIN_KEYS_SK') ?: '',
        'CZ' => getenv('ADYEN_ORIGIN_KEYS_CZ') ?: '',
    ],
    'API_KEYS' => [
        'PL' => getenv('ADYEN_API_KEY_PL') ?: utf8_encode('AQEphmfxK4rNYxxLw0m/n3Q5qf3VZZJ6AoFGXER6axXEMbrOFdpcMdrh61MQwV1bDb7kfNy1WIxIIkxgBw==-m8GFYYJErBtYjjciOM7wbi3vPSdsdigpivb1AqF6mVs=-[;]z4?@R)%rLud7L'),
        'SK' => getenv('ADYEN_API_KEY_SK') ?: '',
        'CZ' => getenv('ADYEN_API_KEY_CZ') ?: '',
    ],
    'MERCHANT_ACCOUNTS' => [
        'PL' => 'MyWorldPolandSpzoo',
        'SK' => '',
        'CZ' => '',
    ],
];

$config[AdyenApiConstants::API_KEY] = $adyenCredentials['API_KEYS'][APPLICATION_STORE];
$config[AdyenConstants::MERCHANT_ACCOUNT] = $adyenCredentials['MERCHANT_ACCOUNTS'][APPLICATION_STORE];
$config[AdyenConstants::SDK_CHECKOUT_ORIGIN_KEY] = $adyenCredentials['ORIGIN_KEYS'][APPLICATION_STORE];

$adyenSplitAccounts = [
    'PL' => '180783198',
    'SK' => '',
    'CZ' => '',
];

$config[AdyenConstants::SPLIT_ACCOUNT] = $adyenSplitAccounts[APPLICATION_STORE];
$config[AdyenConstants::SPLIT_ACCOUNT_COMMISSION_INTEREST] = 0.05;

$config[MyWorldMarketplaceApiConstants::DEALER_ID_DEFAULT] = 'B6AFC7E1-9410-4E82-AA43-AA3000C95203';
