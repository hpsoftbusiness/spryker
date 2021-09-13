<?php
// config file for overriding by cluster


use Pyz\Shared\Adyen\AdyenConstants;
use Pyz\Shared\Locale\LocaleConstants;
use Pyz\Shared\MyWorldMarketplaceApi\MyWorldMarketplaceApiConstants;

$config[LocaleConstants::COUNTRY_TO_LOCALE_RELATIONS] = [
    'IT' => 'it_IT',
    'FR' => 'fr_FR',
    'GR' => 'el_GR',
];

// TODO:: create ORIGIN KEYS for prod environment
$adyenCredentials = [
    'ORIGIN_KEYS' => [
        'IT' => getenv('ADYEN_ORIGIN_KEYS_IT') ?: '',
        'FR' => getenv('ADYEN_ORIGIN_KEYS_FR') ?: '',
        'GR' => getenv('ADYEN_ORIGIN_KEYS_GR') ?: '',
    ],
];

$config[AdyenConstants::SDK_CHECKOUT_ORIGIN_KEY] = $adyenCredentials['ORIGIN_KEYS'][APPLICATION_STORE];

$config[MyWorldMarketplaceApiConstants::DEALER_ID_DEFAULT] = '5AD45F03-6374-4FA6-B2A0-AA3000CCD79E';
