<?php
// config file for overriding by cluster


use Pyz\Shared\Adyen\AdyenConstants;
use Pyz\Shared\Locale\LocaleConstants;
use Pyz\Shared\MyWorldMarketplaceApi\MyWorldMarketplaceApiConstants;

$config[LocaleConstants::COUNTRY_TO_LOCALE_RELATIONS] = [
    'PL' => 'pl_PL',
    'RO' => 'ro_RO',
    'SK' => 'sk_SK',
    'SI' => 'sl_SI',
];

//"originKeys": {
//    "https://pl.myworld.cloud.spryker.toys": "pub.v2.8216083088630330.aHR0cHM6Ly9wbC5teXdvcmxkLmNsb3VkLnNwcnlrZXIudG95cw.IpQtUnXZOvGrLXpy3f-GN3q49G3QYtw-eOOth_XB5ts",
//    "https://ro.myworld.cloud.spryker.toys": "pub.v2.8216083088630330.aHR0cHM6Ly9yby5teXdvcmxkLmNsb3VkLnNwcnlrZXIudG95cw.-zk7N2MKJJ3LYQjOvMEfVn5L5vHf2NJ9TU85_KIv5ig",
//    "https://sk.myworld.cloud.spryker.toys": "pub.v2.8216083088630330.aHR0cHM6Ly9zay5teXdvcmxkLmNsb3VkLnNwcnlrZXIudG95cw.dgUu54Z-lfsaTKy4V_1uTMsA3UgKYWczQuJ_CoEkPgU",
//    "https://sl.myworld.cloud.spryker.toys": "pub.v2.8216083088630330.aHR0cHM6Ly9zbC5teXdvcmxkLmNsb3VkLnNwcnlrZXIudG95cw.K6j25oCuWuYRyu54gBZLXYmT6CHS1IqvHp3URkfuJBA"
//}
$adyenCredentials = [
    'ORIGIN_KEYS' => [
        'PL' => 'pub.v2.8216083088630330.aHR0cHM6Ly9wbC5teXdvcmxkLmNsb3VkLnNwcnlrZXIudG95cw.IpQtUnXZOvGrLXpy3f-GN3q49G3QYtw-eOOth_XB5ts',
        'RO' => 'pub.v2.8216083088630330.aHR0cHM6Ly9yby5teXdvcmxkLmNsb3VkLnNwcnlrZXIudG95cw.-zk7N2MKJJ3LYQjOvMEfVn5L5vHf2NJ9TU85_KIv5ig',
        'SK' => 'pub.v2.8216083088630330.aHR0cHM6Ly9zay5teXdvcmxkLmNsb3VkLnNwcnlrZXIudG95cw.dgUu54Z-lfsaTKy4V_1uTMsA3UgKYWczQuJ_CoEkPgU',
        'SL' => 'pub.v2.8216083088630330.aHR0cHM6Ly9zbC5teXdvcmxkLmNsb3VkLnNwcnlrZXIudG95cw.K6j25oCuWuYRyu54gBZLXYmT6CHS1IqvHp3URkfuJBA',
    ],
];

$config[AdyenConstants::SDK_CHECKOUT_ORIGIN_KEY] = $adyenCredentials['ORIGIN_KEYS'][APPLICATION_STORE];

$config[MyWorldMarketplaceApiConstants::DEALER_ID_DEFAULT] = 'B6AFC7E1-9410-4E82-AA43-AA3000C95203';
