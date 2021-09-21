<?php
// config file for overriding by cluster


use Pyz\Shared\Adyen\AdyenConstants;
use Pyz\Shared\Locale\LocaleConstants;

$config[LocaleConstants::COUNTRY_TO_LOCALE_RELATIONS] = [
    'SI' => 'sl_SI',
    'HR' => 'hr_HR',
    'BA' => 'bs_BA',
    'RS' => 'sr_RS',
    'ME' => 'sr_ME',
];

// TODO:: create ORIGIN KEYS for prod environment
$adyenCredentials = [
    'ORIGIN_KEYS' => [
        'SI' => getenv('ADYEN_ORIGIN_KEYS_SI') ?: '',
        'HR' => getenv('ADYEN_ORIGIN_KEYS_HR') ?: '',
        'BA' => getenv('ADYEN_ORIGIN_KEYS_BA') ?: '',
        'RS' => getenv('ADYEN_ORIGIN_KEYS_RS') ?: '',
        'ME' => getenv('ADYEN_ORIGIN_KEYS_ME') ?: '',
    ],
];

$config[AdyenConstants::SDK_CHECKOUT_ORIGIN_KEY] = $adyenCredentials['ORIGIN_KEYS'][APPLICATION_STORE];
