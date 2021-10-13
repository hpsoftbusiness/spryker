<?php
// config file for overriding by cluster


use Pyz\Shared\Adyen\AdyenConstants;
use Pyz\Shared\Country\CountryConstants;
use Pyz\Shared\Locale\LocaleConstants;
use Pyz\Shared\MyWorldMarketplaceApi\MyWorldMarketplaceApiConstants;

$config[CountryConstants::CLUSTER_COUNTRIES] = ['HU', 'RO', 'MD'];
$config[LocaleConstants::COUNTRY_TO_LOCALE_RELATIONS] = [
    'HU' => 'hu_HU',
    'RO' => 'ro_RO',
    'MD' => 'ro_MD',
];
//"originKeys": {
//    "https://ro.myworld-mca.cloud.spryker.toys": "pub.v2.8216083088630330.aHR0cHM6Ly9yby5teXdvcmxkLW1jYS5jbG91ZC5zcHJ5a2VyLnRveXM.DTVb5DVJ4lbF_wS_WvEmak-BHeG-wLn7Ujul08xTYu8",
//    "https://md.myworld-mca.cloud.spryker.toys": "pub.v2.8216083088630330.aHR0cHM6Ly9tZC5teXdvcmxkLW1jYS5jbG91ZC5zcHJ5a2VyLnRveXM.Tk-Fdivp__9C8WoG4L3sB2L9Q5y3m3qjvju9pBWTt0o",
//    "https://hu.myworld-mca.cloud.spryker.toys": "pub.v2.8216083088630330.aHR0cHM6Ly9odS5teXdvcmxkLW1jYS5jbG91ZC5zcHJ5a2VyLnRveXM.cB8_fI4pgosmv_KIFjReIxk0mtyu3iI3t7RZccalhxo"
//    }
$adyenCredentials = [
    'ORIGIN_KEYS' => [
        'HU' => 'pub.v2.8216083088630330.aHR0cHM6Ly9odS5teXdvcmxkLW1jYS5jbG91ZC5zcHJ5a2VyLnRveXM.cB8_fI4pgosmv_KIFjReIxk0mtyu3iI3t7RZccalhxo',
        'RO' => 'pub.v2.8216083088630330.aHR0cHM6Ly9yby5teXdvcmxkLW1jYS5jbG91ZC5zcHJ5a2VyLnRveXM.DTVb5DVJ4lbF_wS_WvEmak-BHeG-wLn7Ujul08xTYu8',
        'MD' => 'pub.v2.8216083088630330.aHR0cHM6Ly9tZC5teXdvcmxkLW1jYS5jbG91ZC5zcHJ5a2VyLnRveXM.Tk-Fdivp__9C8WoG4L3sB2L9Q5y3m3qjvju9pBWTt0o',
    ],
];

$config[AdyenConstants::SDK_CHECKOUT_ORIGIN_KEY] = $adyenCredentials['ORIGIN_KEYS'][APPLICATION_STORE];

$config[MyWorldMarketplaceApiConstants::DEALER_ID_DEFAULT] = '5E39BA6C-48BC-4F6D-B6AB-AAB000CD695E';
