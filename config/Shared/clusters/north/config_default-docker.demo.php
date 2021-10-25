<?php
// config file for overriding by cluster


use Pyz\Shared\Adyen\AdyenConstants;
use Pyz\Shared\Country\CountryConstants;
use Pyz\Shared\Locale\LocaleConstants;
use Pyz\Shared\MyWorldMarketplaceApi\MyWorldMarketplaceApiConstants;

$config[CountryConstants::CLUSTER_COUNTRIES] = ['NO', 'FI', 'DK', 'SE'];
$config[LocaleConstants::COUNTRY_TO_LOCALE_RELATIONS] = [
    'NO' => 'nb_NO',
    'FI' => 'fi_FI',
    'DK' => 'da_DK',
    'SE' => 'sv_SE',
];

$adyenCredentials = [
    'ORIGIN_KEYS' => [
        'NO' => 'pub.v2.8015571693343839.aHR0cHM6Ly93d3cubm8ubXl3b3JsZC1tY2EuY2xvdWQuc3ByeWtlci50b3lz.LfKWPKRv1hxXhL-dUrW-yQPE1dHsiXN44WqEjHz2oVA',
        'FI' => 'pub.v2.8015571693343839.aHR0cHM6Ly93d3cuZmkubXl3b3JsZC1tY2EuY2xvdWQuc3ByeWtlci50b3lz.QAK4XdSH6j_EKTpId0B4X_BcrN4r2FvrJJdy9GFnRSg',
        'DK' => 'pub.v2.8015571693343839.aHR0cHM6Ly93d3cuZGsubXl3b3JsZC1tY2EuY2xvdWQuc3ByeWtlci50b3lz.MghQOkvISzdIYkvUywCUwxhkC976pZjIwObFXQbrwuU',
        'SE' => 'pub.v2.8015571693343839.aHR0cHM6Ly93d3cuc2UubXl3b3JsZC1tY2EuY2xvdWQuc3ByeWtlci50b3lz.u9Je4N-4DafMbQ7MsjYdWubDD_lRhQKYPOSXOLL7kKs',
    ],
    'MERCHANT_ACCOUNTS' => [
        'NO' => 'MyWorldNordicAS',
        'FI' => '',
        'DK' => '',
        'SE' => '',
    ],
];

$config[AdyenConstants::SDK_CHECKOUT_ORIGIN_KEY] = $adyenCredentials['ORIGIN_KEYS'][APPLICATION_STORE];
$config[AdyenConstants::MERCHANT_ACCOUNT] = $adyenCredentials['MERCHANT_ACCOUNTS'][APPLICATION_STORE];

$config[MyWorldMarketplaceApiConstants::DEALER_ID_DEFAULT] = '31d25f30-e540-4825-b602-adc7009eded8';

// ----------------------------------------------------------------------------
// ------------------ Weclapp integration -------------------------------------
// ----------------------------------------------------------------------------

require 'common/config_weclapp-default.php';
