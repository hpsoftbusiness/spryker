<?php
// config file for overriding by cluster


use Pyz\Shared\Adyen\AdyenConstants;
use Pyz\Shared\Country\CountryConstants;
use Pyz\Shared\Locale\LocaleConstants;
use Pyz\Shared\MyWorldMarketplaceApi\MyWorldMarketplaceApiConstants;

$config[CountryConstants::CLUSTER_COUNTRIES] = ['GB', 'BE', 'IE', 'NL', 'IM'];
$config[LocaleConstants::COUNTRY_TO_LOCALE_RELATIONS] = [
    'GB' => 'en_GB',
    'BE' => [
        'nl_BE',
        'fr_BE',
    ],
    'IE' => 'en_IE',
    'NL' => 'nl_NL',
];

// TODO:: create ORIGIN KEYS for prod environment
$adyenCredentials = [
    'ORIGIN_KEYS' => [
        'GB' => getenv('ADYEN_ORIGIN_KEYS_GB') ?: '',
        'BE' => getenv('ADYEN_ORIGIN_KEYS_BE') ?: '',
        'IE' => getenv('ADYEN_ORIGIN_KEYS_IE') ?: '',
        'NL' => getenv('ADYEN_ORIGIN_KEYS_NL') ?: '',
    ],
];

$config[AdyenConstants::SDK_CHECKOUT_ORIGIN_KEY] = $adyenCredentials['ORIGIN_KEYS'][APPLICATION_STORE];

$config[MyWorldMarketplaceApiConstants::DEALER_ID_DEFAULT] = 'B6AFC7E1-9410-4E82-AA43-AA3000C95203';
