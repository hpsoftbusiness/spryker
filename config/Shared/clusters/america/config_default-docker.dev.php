<?php
// config file for overriding by cluster


use Pyz\Shared\Adyen\AdyenConstants;
use Pyz\Shared\Locale\LocaleConstants;
use Pyz\Shared\MyWorldMarketplaceApi\MyWorldMarketplaceApiConstants;

$config[LocaleConstants::COUNTRY_TO_LOCALE_RELATIONS] = [
    'US' => 'en_US',
];
//"originKeys": {
//    "https://yves.us.myworld.local": "pub.v2.8216083088630330.aHR0cHM6Ly95dmVzLnVzLm15d29ybGQubG9jYWw.JGHfHFmXyDMMaZ6gTvm81fVXFSi74zG8wpScgHh1-Js"
//    }
$adyenCredentials = [
    'ORIGIN_KEYS' => [
        'US' => 'pub.v2.8216083088630330.aHR0cHM6Ly95dmVzLnVzLm15d29ybGQubG9jYWw.JGHfHFmXyDMMaZ6gTvm81fVXFSi74zG8wpScgHh1-Js',
    ],
];

$config[AdyenConstants::SDK_CHECKOUT_ORIGIN_KEY] = $adyenCredentials['ORIGIN_KEYS'][APPLICATION_STORE];

$config[MyWorldMarketplaceApiConstants::DEALER_ID_DEFAULT] = '638cc7c3-afd5-4bcd-9bd1-ac3d008f43bb';
