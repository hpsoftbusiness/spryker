<?php
// config file for overriding by cluster


use Pyz\Shared\Adyen\AdyenConstants;
use Pyz\Shared\Locale\LocaleConstants;

$config[LocaleConstants::COUNTRY_TO_LOCALE_RELATIONS] = [
    'BY' => 'be_BY',
    'UA' => 'uk_UA',
    'RU' => 'ru_RU',
];

// TODO:: create ORIGIN KEYS for prod environment
$adyenCredentials = [
    'ORIGIN_KEYS' => [
        'BY' => getenv('ADYEN_ORIGIN_KEYS_BY') ?: '',
        'UA' => getenv('ADYEN_ORIGIN_KEYS_UA') ?: '',
        'RU' => getenv('ADYEN_ORIGIN_KEYS_RU') ?: '',
    ],
];

$config[AdyenConstants::SDK_CHECKOUT_ORIGIN_KEY] = $adyenCredentials['ORIGIN_KEYS'][APPLICATION_STORE];
