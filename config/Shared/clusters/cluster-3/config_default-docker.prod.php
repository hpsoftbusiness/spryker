<?php
// config file for overriding by cluster


use Pyz\Shared\Adyen\AdyenConstants;
use Pyz\Shared\Locale\LocaleConstants;
use Pyz\Shared\MyWorldMarketplaceApi\MyWorldMarketplaceApiConstants;

$config[LocaleConstants::COUNTRY_TO_LOCALE_RELATIONS] = [
    'PL' => 'pl_PL',
    'RO' => 'ro_RO',
    'SK' => 'sk_SK',
    'SL' => 'sl_SI',
];

// TODO:: create ORIGIN KEYS for prod environment
$adyenCredentials = [
    'ORIGIN_KEYS' => [
        'PL' => getenv('ADYEN_ORIGIN_KEYS_PL') ?: '',
        'RO' => getenv('ADYEN_ORIGIN_KEYS_RO') ?: '',
        'SK' => getenv('ADYEN_ORIGIN_KEYS_SK') ?: '',
        'SL' => getenv('ADYEN_ORIGIN_KEYS_SL') ?: '',
    ],
];

$config[AdyenConstants::SDK_CHECKOUT_ORIGIN_KEY] = $adyenCredentials['ORIGIN_KEYS'][APPLICATION_STORE];

$config[MyWorldMarketplaceApiConstants::DEALER_ID_DEFAULT] = 'B6AFC7E1-9410-4E82-AA43-AA3000C95203';
