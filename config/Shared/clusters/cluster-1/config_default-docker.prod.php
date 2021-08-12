<?php
// config file for overriding by cluster


use Pyz\Shared\Adyen\AdyenConstants;
use Pyz\Shared\Locale\LocaleConstants;
use Pyz\Shared\MyWorldMarketplaceApi\MyWorldMarketplaceApiConstants;

$config[LocaleConstants::COUNTRY_TO_LOCALE_RELATIONS] = [
    'AT' => 'de_AT',
    'DE' => 'de_DE',
];

// TODO:: create ORIGIN KEYS for prod environment
$adyenCredentials = [
    'ORIGIN_KEYS' => [
        'AT' => getenv('ADYEN_ORIGIN_KEYS_AT') ?: '',
        'DE' => getenv(
            'ADYEN_ORIGIN_KEYS_DE'
        ) ?: 'pub.v2.4116119410741591.aHR0cHM6Ly93d3cubWFya2V0cGxhY2UubXl3b3JsZC5jb20.4WwTYl6NQnAxJJ_N3OmG10fBr6W-UMkOT9JvngAz6EY',
    ],
];

$config[AdyenConstants::SDK_CHECKOUT_ORIGIN_KEY] = $adyenCredentials['ORIGIN_KEYS'][APPLICATION_STORE];

$config[MyWorldMarketplaceApiConstants::DEALER_ID_DEFAULT] = 'BA3E82A7-BBC4-4874-A383-AA3100985CC9';
