<?php
// config file for overriding by cluster


use Pyz\Shared\Adyen\AdyenConstants;
use Pyz\Shared\Locale\LocaleConstants;
use Pyz\Shared\MyWorldMarketplaceApi\MyWorldMarketplaceApiConstants;

$config[LocaleConstants::COUNTRY_TO_LOCALE_RELATIONS] = [
    'US' => 'en_US',
];

// TODO:: create ORIGIN KEYS for prod environment
$adyenCredentials = [
    'ORIGIN_KEYS' => [
        'US' => getenv('ADYEN_ORIGIN_KEYS_US') ?: '',
    ],
];

$config[AdyenConstants::SDK_CHECKOUT_ORIGIN_KEY] = $adyenCredentials['ORIGIN_KEYS'][APPLICATION_STORE];

$config[MyWorldMarketplaceApiConstants::DEALER_ID_DEFAULT] = '638cc7c3-afd5-4bcd-9bd1-ac3d008f43bb';
