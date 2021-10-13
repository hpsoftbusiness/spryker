<?php
// config file for overriding by cluster


use Pyz\Shared\Adyen\AdyenConstants;
use Pyz\Shared\Country\CountryConstants;
use Pyz\Shared\Locale\LocaleConstants;

$config[CountryConstants::CLUSTER_COUNTRIES] = ['MY', 'HK', 'AU', 'NZ', 'PH', 'TH', 'MO'];
$config[LocaleConstants::COUNTRY_TO_LOCALE_RELATIONS] = [
    'MY' => 'en_US',
    'HK' => 'en_US',
    'AU' => 'en_US',
    'NZ' => 'en_US',
    'PH' => 'en_US',
    'TH' => 'en_US',
    'MO' => 'en_US',
];

// TODO:: create ORIGIN KEYS for prod environment
$adyenCredentials = [
    'ORIGIN_KEYS' => [
        'MY' => getenv('ADYEN_ORIGIN_KEYS_MY') ?: '',
        'HK' => getenv('ADYEN_ORIGIN_KEYS_HK') ?: '',
        'AU' => getenv('ADYEN_ORIGIN_KEYS_AU') ?: '',
        'NZ' => getenv('ADYEN_ORIGIN_KEYS_NZ') ?: '',
        'PH' => getenv('ADYEN_ORIGIN_KEYS_PH') ?: '',
        'TH' => getenv('ADYEN_ORIGIN_KEYS_TH') ?: '',
        'MO' => getenv('ADYEN_ORIGIN_KEYS_MO') ?: '',
    ],
];

$config[AdyenConstants::SDK_CHECKOUT_ORIGIN_KEY] = $adyenCredentials['ORIGIN_KEYS'][APPLICATION_STORE];
