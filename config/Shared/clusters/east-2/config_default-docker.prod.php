<?php
// config file for overriding by cluster


use Pyz\Shared\Adyen\AdyenConstants;
use Pyz\Shared\Locale\LocaleConstants;
use Pyz\Shared\MyWorldMarketplaceApi\MyWorldMarketplaceApiConstants;

$config[LocaleConstants::COUNTRY_TO_LOCALE_RELATIONS] = [
    'HU' => 'hu_HU',
    'RO' => 'ro_MD',
    'MD' => 'ro_RO',
];

// TODO:: create ORIGIN KEYS for prod environment
$adyenCredentials = [
    'ORIGIN_KEYS' => [
        'HU' => getenv('ADYEN_ORIGIN_KEYS_HU') ?: '',
        'RO' => getenv('ADYEN_ORIGIN_KEYS_RO') ?: '',
        'MD' => getenv('ADYEN_ORIGIN_KEYS_MD') ?: '',
    ],
];

$config[AdyenConstants::SDK_CHECKOUT_ORIGIN_KEY] = $adyenCredentials['ORIGIN_KEYS'][APPLICATION_STORE];

$config[MyWorldMarketplaceApiConstants::DEALER_ID_DEFAULT] = '5E39BA6C-48BC-4F6D-B6AB-AAB000CD695E';
