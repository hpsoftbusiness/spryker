<?php
// config file for overriding by cluster


use Pyz\Shared\Adyen\AdyenConstants;
use Pyz\Shared\Locale\LocaleConstants;
use Pyz\Shared\MyWorldMarketplaceApi\MyWorldMarketplaceApiConstants;

$config[LocaleConstants::COUNTRY_TO_LOCALE_RELATIONS] = [
    'PL' => 'pl_PL',
    'RO' => 'ro_RO',
    'SK' => 'sk_SK',
    'SL' => 'sl_SI',
];

//"originKeys": {
//    "https://pl.myworld-mca.cloud.spryker.toys": "pub.v2.8216083088630330.aHR0cHM6Ly9wbC5teXdvcmxkLW1jYS5jbG91ZC5zcHJ5a2VyLnRveXM.qLhpUh--skSQMZFYhK56Kn4O_HthI0MkoPWgyzo9Tyc",
//    "https://ro.myworld-mca.cloud.spryker.toys": "pub.v2.8216083088630330.aHR0cHM6Ly9yby5teXdvcmxkLW1jYS5jbG91ZC5zcHJ5a2VyLnRveXM.DTVb5DVJ4lbF_wS_WvEmak-BHeG-wLn7Ujul08xTYu8",
//    "https://sk.myworld-mca.cloud.spryker.toys": "pub.v2.8216083088630330.aHR0cHM6Ly9zay5teXdvcmxkLW1jYS5jbG91ZC5zcHJ5a2VyLnRveXM.YUjhq_XcgKBCfIPDqEpG2Y23eUt5nD4HYeCB0i9wc_I",
//    "https://sl.myworld-mca.cloud.spryker.toys": "pub.v2.8216083088630330.aHR0cHM6Ly9zbC5teXdvcmxkLW1jYS5jbG91ZC5zcHJ5a2VyLnRveXM.mkb5Uy56hphCnvZxNNA07n8nDJJmh0ekaga5EpM21kI"
//    }
$adyenCredentials = [
    'ORIGIN_KEYS' => [
        'PL' => 'pub.v2.8216083088630330.aHR0cHM6Ly9wbC5teXdvcmxkLW1jYS5jbG91ZC5zcHJ5a2VyLnRveXM.qLhpUh--skSQMZFYhK56Kn4O_HthI0MkoPWgyzo9Tyc',
        'RO' => 'pub.v2.8216083088630330.aHR0cHM6Ly9yby5teXdvcmxkLW1jYS5jbG91ZC5zcHJ5a2VyLnRveXM.DTVb5DVJ4lbF_wS_WvEmak-BHeG-wLn7Ujul08xTYu8',
        'SK' => 'pub.v2.8216083088630330.aHR0cHM6Ly9zay5teXdvcmxkLW1jYS5jbG91ZC5zcHJ5a2VyLnRveXM.YUjhq_XcgKBCfIPDqEpG2Y23eUt5nD4HYeCB0i9wc_I',
        'SL' => 'pub.v2.8216083088630330.aHR0cHM6Ly9zbC5teXdvcmxkLW1jYS5jbG91ZC5zcHJ5a2VyLnRveXM.mkb5Uy56hphCnvZxNNA07n8nDJJmh0ekaga5EpM21kI',
    ],
];

$config[AdyenConstants::SDK_CHECKOUT_ORIGIN_KEY] = $adyenCredentials['ORIGIN_KEYS'][APPLICATION_STORE];

$config[MyWorldMarketplaceApiConstants::DEALER_ID_DEFAULT] = 'B6AFC7E1-9410-4E82-AA43-AA3000C95203';
