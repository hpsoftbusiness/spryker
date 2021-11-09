<?php
// config file for overriding by cluster


use Pyz\Shared\Adyen\AdyenConstants;
use Pyz\Shared\Country\CountryConstants;
use Pyz\Shared\Locale\LocaleConstants;
use Pyz\Shared\MyWorldMarketplaceApi\MyWorldMarketplaceApiConstants;

$config[CountryConstants::CLUSTER_COUNTRIES] = ['PL', 'SK', 'CZ'];

$config[LocaleConstants::COUNTRY_TO_LOCALE_RELATIONS] = [
    'PL' => 'pl_PL',
    'SK' => 'sk_SK',
    'CZ' => 'cs_CZ',
];

$adyenCredentials = [
    'ORIGIN_KEYS' => [
        'PL' => 'pub.v2.8216083088630330.aHR0cHM6Ly95dmVzLnBsLm15d29ybGQubG9jYWw.cdJ-tDOyXDbQEN07IhyAihosuHZo5wDSZM9l9z8XRYQ',
        'SK' => 'pub.v2.8216083088630330.aHR0cHM6Ly95dmVzLnNrLm15d29ybGQubG9jYWw.1p_N7z3WBHS1wNNa915j2jOirXIy_4CAm5KgczmZ_EE',
        'CZ' => 'pub.v2.8216068210552874.aHR0cHM6Ly95dmVzLmN6Lm15d29ybGQubG9jYWw.WQscpH7PDRVHPEWU0LwILxTzxCQcNeETbOT4xdRLoCo',
    ],
    'MERCHANT_ACCOUNTS' => [
        'PL' => 'MyWorldPolandSpzoo',
        'SK' => 'MyWorldSlovakiaSro',
        'CZ' => 'MyWorldsro',
    ],
];

$config[AdyenConstants::SDK_CHECKOUT_ORIGIN_KEY] = $adyenCredentials['ORIGIN_KEYS'][APPLICATION_STORE];
$config[AdyenConstants::MERCHANT_ACCOUNT] = $adyenCredentials['MERCHANT_ACCOUNTS'][APPLICATION_STORE];

$config[MyWorldMarketplaceApiConstants::DEALER_ID_DEFAULT] = 'B6AFC7E1-9410-4E82-AA43-AA3000C95203';

$config[AdyenConstants::SPLIT_ACCOUNT_COMMISSION_INTEREST] = 0;
