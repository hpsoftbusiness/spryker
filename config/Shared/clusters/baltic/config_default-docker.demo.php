<?php
// config file for overriding by cluster


use Pyz\Shared\Adyen\AdyenConstants;
use Pyz\Shared\Locale\LocaleConstants;
use Pyz\Shared\MyWorldMarketplaceApi\MyWorldMarketplaceApiConstants;

$config[LocaleConstants::COUNTRY_TO_LOCALE_RELATIONS] = [
    'EE' => 'et_EE',
    'LV' => 'lv_LV',
    'LT' => 'lt_LT',
];

//"originKeys": {
//    "https://lt.myworld-mca.cloud.spryker.toys": "pub.v2.8216083088630330.aHR0cHM6Ly9sdC5teXdvcmxkLW1jYS5jbG91ZC5zcHJ5a2VyLnRveXM.Cl9U4rknrCe7sMj2oLjpSq1XKJIe6PqOROFhXXTeg_0",
//    "https://ee.myworld-mca.cloud.spryker.toys": "pub.v2.8216083088630330.aHR0cHM6Ly9lZS5teXdvcmxkLW1jYS5jbG91ZC5zcHJ5a2VyLnRveXM.dN8E9k_c4wg1Gu1iEdz9cqskpjlmQr8EFkmJZgJvbsw",
//    "https://lv.myworld-mca.cloud.spryker.toys": "pub.v2.8216083088630330.aHR0cHM6Ly9sdi5teXdvcmxkLW1jYS5jbG91ZC5zcHJ5a2VyLnRveXM.PvQ-MrV67UVvGUBfWpepMensATID1xOEY0En0IW_K18"
//    }
$adyenCredentials = [
    'ORIGIN_KEYS' => [
        'EE' => 'pub.v2.8216083088630330.aHR0cHM6Ly9lZS5teXdvcmxkLW1jYS5jbG91ZC5zcHJ5a2VyLnRveXM.dN8E9k_c4wg1Gu1iEdz9cqskpjlmQr8EFkmJZgJvbsw',
        'LV' => 'pub.v2.8216083088630330.aHR0cHM6Ly9sdi5teXdvcmxkLW1jYS5jbG91ZC5zcHJ5a2VyLnRveXM.PvQ-MrV67UVvGUBfWpepMensATID1xOEY0En0IW_K18',
        'LT' => 'pub.v2.8216083088630330.aHR0cHM6Ly9sdC5teXdvcmxkLW1jYS5jbG91ZC5zcHJ5a2VyLnRveXM.Cl9U4rknrCe7sMj2oLjpSq1XKJIe6PqOROFhXXTeg_0',
    ],
];

$config[AdyenConstants::SDK_CHECKOUT_ORIGIN_KEY] = $adyenCredentials['ORIGIN_KEYS'][APPLICATION_STORE];

$config[MyWorldMarketplaceApiConstants::DEALER_ID_DEFAULT] = '0B13941A-CFE5-4523-BD45-AABF00CACC73';
