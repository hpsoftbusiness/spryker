<?php
// config file for overriding by cluster


use Pyz\Shared\Adyen\AdyenConstants;
use Pyz\Shared\Country\CountryConstants;
use Pyz\Shared\Locale\LocaleConstants;
use Pyz\Shared\MyWorldMarketplaceApi\MyWorldMarketplaceApiConstants;

$config[CountryConstants::CLUSTER_COUNTRIES] = ['ES', 'PT'];
$config[LocaleConstants::COUNTRY_TO_LOCALE_RELATIONS] = [
    'ES' => 'es_ES',
    'PT' => 'pt_PT',
];

// TODO:: create ORIGIN KEYS for prod environment
$adyenCredentials = [
    'ORIGIN_KEYS' => [
        'ES' => getenv('ADYEN_ORIGIN_KEYS_ES') ?: '',
        'PT' => getenv('ADYEN_ORIGIN_KEYS_PT') ?: '',
    ],
];

$config[AdyenConstants::SDK_CHECKOUT_ORIGIN_KEY] = $adyenCredentials['ORIGIN_KEYS'][APPLICATION_STORE];

$config[MyWorldMarketplaceApiConstants::DEALER_ID_DEFAULT] = '58FBDDD4-CD67-4F9E-96A1-AAC000564B0E';
