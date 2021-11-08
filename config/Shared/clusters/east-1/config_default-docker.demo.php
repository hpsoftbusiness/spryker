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
        'PL' => 'pub.v2.8216083088630330.aHR0cHM6Ly9wbC5teXdvcmxkLW1jYS5jbG91ZC5zcHJ5a2VyLnRveXM.qLhpUh--skSQMZFYhK56Kn4O_HthI0MkoPWgyzo9Tyc',
        'SK' => 'pub.v2.8216083088630330.aHR0cHM6Ly93d3cuc2subXl3b3JsZC1tY2EuY2xvdWQuc3ByeWtlci50b3lz.5mxWBaCX3b3wYTAbmvn-tp26R0ygEmRCFlWMiFocsL4',
        'CZ' => 'pub.v2.8216083088630330.aHR0cHM6Ly93d3cuY3oubXl3b3JsZC1tY2EuY2xvdWQuc3ByeWtlci50b3lz.7fm_0ORnp-x7PeBnWhiXkjnWDUXFqh6H8KWxh4Zk30o',
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
