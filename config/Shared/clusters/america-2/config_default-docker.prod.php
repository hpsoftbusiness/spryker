<?php
// config file for overriding by cluster


use Pyz\Shared\Adyen\AdyenConstants;
use Pyz\Shared\Locale\LocaleConstants;

$config[LocaleConstants::COUNTRY_TO_LOCALE_RELATIONS] = [
    'CA' => 'fr_CA',
    'BR' => 'pt_BR',
    'CO' => 'es_CO',
    'MX' => 'es_MX',
];

// TODO:: create ORIGIN KEYS for prod environment
$adyenCredentials = [
    'ORIGIN_KEYS' => [
        'CA' => getenv('ADYEN_ORIGIN_KEYS_CA') ?: '',
        'BR' => getenv('ADYEN_ORIGIN_KEYS_BR') ?: '',
        'CO' => getenv('ADYEN_ORIGIN_KEYS_CO') ?: '',
        'MX' => getenv('ADYEN_ORIGIN_KEYS_MX') ?: '',
    ],
];

$config[AdyenConstants::SDK_CHECKOUT_ORIGIN_KEY] = $adyenCredentials['ORIGIN_KEYS'][APPLICATION_STORE];
