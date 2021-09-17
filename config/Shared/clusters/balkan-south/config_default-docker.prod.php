<?php
// config file for overriding by cluster


use Pyz\Shared\Adyen\AdyenConstants;
use Pyz\Shared\Locale\LocaleConstants;

$config[LocaleConstants::COUNTRY_TO_LOCALE_RELATIONS] = [
    'BG' => 'bg_BG',
    'MK' => 'mk_MK',
    'AL' => 'sq_AL',
    'XK' => 'sr_RS',
];

// TODO:: create ORIGIN KEYS for prod environment
$adyenCredentials = [
    'ORIGIN_KEYS' => [
        'BG' => getenv('ADYEN_ORIGIN_KEYS_BG') ?: '',
        'MK' => getenv('ADYEN_ORIGIN_KEYS_MK') ?: '',
        'AL' => getenv('ADYEN_ORIGIN_KEYS_AL') ?: '',
        'XK' => getenv('ADYEN_ORIGIN_KEYS_XK') ?: '',
    ],
];

$config[AdyenConstants::SDK_CHECKOUT_ORIGIN_KEY] = $adyenCredentials['ORIGIN_KEYS'][APPLICATION_STORE];
