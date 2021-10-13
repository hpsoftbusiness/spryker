<?php
// config file for overriding by cluster


use Pyz\Shared\Adyen\AdyenConstants;
use Pyz\Shared\Country\CountryConstants;
use Pyz\Shared\Locale\LocaleConstants;
use Pyz\Shared\MyWorldMarketplaceApi\MyWorldMarketplaceApiConstants;

$config[CountryConstants::CLUSTER_COUNTRIES] = ['EE', 'LV', 'LT'];
$config[LocaleConstants::COUNTRY_TO_LOCALE_RELATIONS] = [
    'EE' => 'et_EE',
    'LV' => 'lv_LV',
    'LT' => 'lt_LT',
];

//"originKeys": {
//    "https://yves.ee.myworld.local": "pub.v2.8216083088630330.aHR0cHM6Ly95dmVzLmVlLm15d29ybGQubG9jYWw.lz801zMfuTE7h1TU7vxQxq-Sj00LrfqdmMgu1YtB9ZQ",
//    "https://yves.lt.myworld.local": "pub.v2.8216083088630330.aHR0cHM6Ly95dmVzLmx0Lm15d29ybGQubG9jYWw.jXEMVIdM-bnmcG9Xv229kOfI3GVytba05R4qR5hushU",
//    "https://yves.lv.myworld.local": "pub.v2.8216083088630330.aHR0cHM6Ly95dmVzLmx2Lm15d29ybGQubG9jYWw.BrVTFjhU4fxPc1Hc_4EU8vPfZ1rQ4GD3HomaG3OYxjo"
//    }
$adyenCredentials = [
    'ORIGIN_KEYS' => [
        'EE' => 'pub.v2.8216083088630330.aHR0cHM6Ly95dmVzLmVlLm15d29ybGQubG9jYWw.lz801zMfuTE7h1TU7vxQxq-Sj00LrfqdmMgu1YtB9ZQ',
        'LV' => 'pub.v2.8216083088630330.aHR0cHM6Ly95dmVzLmx2Lm15d29ybGQubG9jYWw.BrVTFjhU4fxPc1Hc_4EU8vPfZ1rQ4GD3HomaG3OYxjo',
        'LT' => 'pub.v2.8216083088630330.aHR0cHM6Ly95dmVzLmx0Lm15d29ybGQubG9jYWw.jXEMVIdM-bnmcG9Xv229kOfI3GVytba05R4qR5hushU',
    ],
];

$config[AdyenConstants::SDK_CHECKOUT_ORIGIN_KEY] = $adyenCredentials['ORIGIN_KEYS'][APPLICATION_STORE];

$config[MyWorldMarketplaceApiConstants::DEALER_ID_DEFAULT] = '0B13941A-CFE5-4523-BD45-AABF00CACC73';
