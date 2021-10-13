<?php
// config file for overriding by cluster


use Pyz\Shared\Adyen\AdyenConstants;
use Pyz\Shared\Country\CountryConstants;
use Pyz\Shared\Locale\LocaleConstants;
use Pyz\Shared\MyWorldMarketplaceApi\MyWorldMarketplaceApiConstants;

$config[CountryConstants::CLUSTER_COUNTRIES] = ['EE', 'LV', 'LT'];
$config[LocaleConstants::COUNTRY_TO_LOCALE_RELATIONS] = [
    'EE' => 'et_EE',
    'LV' => 'lv_LV',
    'LT' => 'lt_LT',
];

// TODO:: create ORIGIN KEYS for prod environment
$adyenCredentials = [
    'ORIGIN_KEYS' => [
        'EE' => getenv('ADYEN_ORIGIN_KEYS_EE') ?: '',
        'LV' => getenv('ADYEN_ORIGIN_KEYS_LV') ?: '',
        'LT' => getenv('ADYEN_ORIGIN_KEYS_LT') ?: '',
    ],
];

$config[AdyenConstants::SDK_CHECKOUT_ORIGIN_KEY] = $adyenCredentials['ORIGIN_KEYS'][APPLICATION_STORE];

$config[MyWorldMarketplaceApiConstants::DEALER_ID_DEFAULT] = '0B13941A-CFE5-4523-BD45-AABF00CACC73';
