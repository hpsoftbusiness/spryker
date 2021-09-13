<?php
// config file for overriding by cluster


use Pyz\Shared\Adyen\AdyenConstants;
use Pyz\Shared\Locale\LocaleConstants;
use Pyz\Shared\MyWorldMarketplaceApi\MyWorldMarketplaceApiConstants;

$config[LocaleConstants::COUNTRY_TO_LOCALE_RELATIONS] = [
    'ES' => 'es_ES',
    'PT' => 'pt_PT',
];
//"originKeys": {
//    "https://es.myworld-mca.cloud.spryker.toys": "pub.v2.8216083088630330.aHR0cHM6Ly9lcy5teXdvcmxkLW1jYS5jbG91ZC5zcHJ5a2VyLnRveXM.1GZByxWEVz7Jpt5AqiRKqgZ7pt3PVBc_JspemKw2wpQ",
//    "https://pt.myworld-mca.cloud.spryker.toys": "pub.v2.8216083088630330.aHR0cHM6Ly9wdC5teXdvcmxkLW1jYS5jbG91ZC5zcHJ5a2VyLnRveXM.yty19o0j23IxTzbpp-F0it_JWAHEjv0ke4rfyvdnIrs"
//    }
$adyenCredentials = [
    'ORIGIN_KEYS' => [
        'ES' => 'pub.v2.8216083088630330.aHR0cHM6Ly9lcy5teXdvcmxkLW1jYS5jbG91ZC5zcHJ5a2VyLnRveXM.1GZByxWEVz7Jpt5AqiRKqgZ7pt3PVBc_JspemKw2wpQ',
        'PT' => 'pub.v2.8216083088630330.aHR0cHM6Ly9wdC5teXdvcmxkLW1jYS5jbG91ZC5zcHJ5a2VyLnRveXM.yty19o0j23IxTzbpp-F0it_JWAHEjv0ke4rfyvdnIrs',
    ],
];

$config[AdyenConstants::SDK_CHECKOUT_ORIGIN_KEY] = $adyenCredentials['ORIGIN_KEYS'][APPLICATION_STORE];

$config[MyWorldMarketplaceApiConstants::DEALER_ID_DEFAULT] = '58FBDDD4-CD67-4F9E-96A1-AAC000564B0E';
