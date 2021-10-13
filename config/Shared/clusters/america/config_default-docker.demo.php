<?php
// config file for overriding by cluster


use Pyz\Shared\Adyen\AdyenConstants;
use Pyz\Shared\Country\CountryConstants;
use Pyz\Shared\Locale\LocaleConstants;
use Pyz\Shared\MyWorldMarketplaceApi\MyWorldMarketplaceApiConstants;

$config[CountryConstants::CLUSTER_COUNTRIES] = ['US'];
$config[LocaleConstants::COUNTRY_TO_LOCALE_RELATIONS] = [
    'US' => 'en_US',
];

//"originKeys": {
//    "https://us.myworld-mca.cloud.spryker.toys": "pub.v2.8216083088630330.aHR0cHM6Ly91cy5teXdvcmxkLW1jYS5jbG91ZC5zcHJ5a2VyLnRveXM.jtT1JdvsQvQ_JTZl43WeyyKuYYG9Tako67qk1AIHqCc"
//    }
$adyenCredentials = [
    'ORIGIN_KEYS' => [
        'US' => 'pub.v2.8216083088630330.aHR0cHM6Ly91cy5teXdvcmxkLW1jYS5jbG91ZC5zcHJ5a2VyLnRveXM.jtT1JdvsQvQ_JTZl43WeyyKuYYG9Tako67qk1AIHqCc',
    ],
];

$config[AdyenConstants::SDK_CHECKOUT_ORIGIN_KEY] = $adyenCredentials['ORIGIN_KEYS'][APPLICATION_STORE];

$config[MyWorldMarketplaceApiConstants::DEALER_ID_DEFAULT] = '638cc7c3-afd5-4bcd-9bd1-ac3d008f43bb';
