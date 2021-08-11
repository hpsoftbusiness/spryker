<?php
// config file for overriding by cluster


use Pyz\Shared\Adyen\AdyenConstants;
use Pyz\Shared\Locale\LocaleConstants;
use Pyz\Shared\MyWorldMarketplaceApi\MyWorldMarketplaceApiConstants;

$config[LocaleConstants::COUNTRY_TO_LOCALE_RELATIONS] = [
    'AT' => 'de_DE',
    'DE' => 'de_DE',
];

//"originKeys": {
//    "https://yves.at.myworld.local": "pub.v2.8216083088630330.aHR0cHM6Ly95dmVzLmF0Lm15d29ybGQubG9jYWw.sBKoMF2gveV7BiYyI1aX2Z3sM1i0D_XYi-nMJz815Es",
//    "https://yves.de.myworld.local": "pub.v2.8216083088630330.aHR0cHM6Ly95dmVzLmRlLm15d29ybGQubG9jYWw.Oj2BH6hYCxdKyhN-clRm33LZxptO0fHBRcawFOJ0fOw"
//}
$adyenCredentials = [
    'ORIGIN_KEYS' => [
        'AT' => 'pub.v2.8216083088630330.aHR0cHM6Ly95dmVzLmF0Lm15d29ybGQubG9jYWw.sBKoMF2gveV7BiYyI1aX2Z3sM1i0D_XYi-nMJz815Es',
        'DE' => 'pub.v2.8216083088630330.aHR0cHM6Ly95dmVzLmRlLm15d29ybGQubG9jYWw.Oj2BH6hYCxdKyhN-clRm33LZxptO0fHBRcawFOJ0fOw',
    ],
];

$config[AdyenConstants::SDK_CHECKOUT_ORIGIN_KEY] = $adyenCredentials['ORIGIN_KEYS'][APPLICATION_STORE];

$config[MyWorldMarketplaceApiConstants::DEALER_ID_DEFAULT] = 'BA3E82A7-BBC4-4874-A383-AA3100985CC9';
