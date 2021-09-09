<?php
// config file for overriding by cluster


use Pyz\Shared\Adyen\AdyenConstants;
use Pyz\Shared\Locale\LocaleConstants;
use Pyz\Shared\MyWorldMarketplaceApi\MyWorldMarketplaceApiConstants;

$config[LocaleConstants::COUNTRY_TO_LOCALE_RELATIONS] = [
    'NO' => 'nn_NO',
    'FI' => 'fi_FI',
    'DK' => 'da_DK',
    'SE' => 'sv_SE',
];
//"originKeys": {
//     "https://dk.myworld-mca.cloud.spryker.toys": "pub.v2.8216083088630330.aHR0cHM6Ly9kay5teXdvcmxkLW1jYS5jbG91ZC5zcHJ5a2VyLnRveXM.MnoAGDbiKPyqKSe2ok6Me2Aj0EemstrMIa8odi1Do5U",
//     "https://se.myworld-mca.cloud.spryker.toys": "pub.v2.8216083088630330.aHR0cHM6Ly9zZS5teXdvcmxkLW1jYS5jbG91ZC5zcHJ5a2VyLnRveXM.r1YaGnReXBzkj0idykXeuLLLrSXCCZH8pgulns7uooY",
//     "https://no.myworld-mca.cloud.spryker.toys": "pub.v2.8216083088630330.aHR0cHM6Ly9uby5teXdvcmxkLW1jYS5jbG91ZC5zcHJ5a2VyLnRveXM.G_DoOgvix2eWWbHeJb55nc1S0Lj1a32K-3965s0MPRo",
//     "https://fi.myworld-mca.cloud.spryker.toys": "pub.v2.8216083088630330.aHR0cHM6Ly9maS5teXdvcmxkLW1jYS5jbG91ZC5zcHJ5a2VyLnRveXM.Fv8r0jFbz6idaj58KfcmJnNdSrKrJtAs543Ws2HuEZ0"
//    }
$adyenCredentials = [
    'ORIGIN_KEYS' => [
        'NO' => 'pub.v2.8216083088630330.aHR0cHM6Ly9kay5teXdvcmxkLW1jYS5jbG91ZC5zcHJ5a2VyLnRveXM.MnoAGDbiKPyqKSe2ok6Me2Aj0EemstrMIa8odi1Do5U',
        'FI' => 'pub.v2.8216083088630330.aHR0cHM6Ly9zZS5teXdvcmxkLW1jYS5jbG91ZC5zcHJ5a2VyLnRveXM.r1YaGnReXBzkj0idykXeuLLLrSXCCZH8pgulns7uooY',
        'DK' => 'pub.v2.8216083088630330.aHR0cHM6Ly9uby5teXdvcmxkLW1jYS5jbG91ZC5zcHJ5a2VyLnRveXM.G_DoOgvix2eWWbHeJb55nc1S0Lj1a32K-3965s0MPRo',
        'SE' => 'pub.v2.8216083088630330.aHR0cHM6Ly9maS5teXdvcmxkLW1jYS5jbG91ZC5zcHJ5a2VyLnRveXM.Fv8r0jFbz6idaj58KfcmJnNdSrKrJtAs543Ws2HuEZ0',
    ],
];

$config[AdyenConstants::SDK_CHECKOUT_ORIGIN_KEY] = $adyenCredentials['ORIGIN_KEYS'][APPLICATION_STORE];

$config[MyWorldMarketplaceApiConstants::DEALER_ID_DEFAULT] = '8F8D1A16-E266-4906-9528-AA310068B044';
