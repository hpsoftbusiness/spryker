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
        'NO' => 'pub.v2.8216083088630330.aHR0cHM6Ly95dmVzLm5vLm15d29ybGQubG9jYWw.1xxQdViYRpbHwd1CW6kC-KEajxiM4d7a8rAfiMTzslE',
        'FI' => 'pub.v2.8216083088630330.aHR0cHM6Ly95dmVzLmZpLm15d29ybGQubG9jYWw.G_j8xZJxufm3E92I-wXZW2fphYUa1NOoHHgK48TFUnA',
        'DK' => 'pub.v2.8216083088630330.aHR0cHM6Ly95dmVzLmRrLm15d29ybGQubG9jYWw.y8j8x2aeIHu22B2Fxbf3wOPJHqyVk0MQynqIsLD-aMU',
        'SE' => 'pub.v2.8216083088630330.aHR0cHM6Ly95dmVzLnNlLm15d29ybGQubG9jYWw.4dEYOcii01AmLbFub10jgrKNqjJJDgcT4Vh2-iyr7dM',
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
