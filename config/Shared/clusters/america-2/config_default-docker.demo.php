<?php
// config file for overriding by cluster


use Pyz\Shared\Adyen\AdyenConstants;
use Pyz\Shared\Country\CountryConstants;
use Pyz\Shared\Locale\LocaleConstants;

$config[CountryConstants::CLUSTER_COUNTRIES] = ['CA', 'BR', 'CO', 'MX'];
$config[LocaleConstants::COUNTRY_TO_LOCALE_RELATIONS] = [
    'CA' => 'fr_CA',
    'BR' => 'pt_BR',
    'CO' => 'es_CO',
    'MX' => 'es_MX',
];
//"originKeys": {
//    "https://co.myworld-mca.cloud.spryker.toys": "pub.v2.8216083088630330.aHR0cHM6Ly9jby5teXdvcmxkLW1jYS5jbG91ZC5zcHJ5a2VyLnRveXM.ryimjZ0By99LaHSqKD_wWcWZsV2B1u0zGCLh2Cir17U",
//    "https://mx.myworld-mca.cloud.spryker.toys": "pub.v2.8216083088630330.aHR0cHM6Ly9teC5teXdvcmxkLW1jYS5jbG91ZC5zcHJ5a2VyLnRveXM.GVdLlBU1PK2roRO2dKCT727m3CIyqA5POTJJTblT884",
//    "https://br.myworld-mca.cloud.spryker.toys": "pub.v2.8216083088630330.aHR0cHM6Ly9ici5teXdvcmxkLW1jYS5jbG91ZC5zcHJ5a2VyLnRveXM.6FgBOez3quSqoWhn6-OnMCd3JuPl6u-voku9dA4VF-Q",
//    "https://ca.myworld-mca.cloud.spryker.toys": "pub.v2.8216083088630330.aHR0cHM6Ly9jYS5teXdvcmxkLW1jYS5jbG91ZC5zcHJ5a2VyLnRveXM.nrve_aGXp_Ow667CgFHZiDjVfik_a_WSqGK5Ls_PhIQ"
//    }
$adyenCredentials = [
    'ORIGIN_KEYS' => [
        'CA' => 'pub.v2.8216083088630330.aHR0cHM6Ly9jYS5teXdvcmxkLW1jYS5jbG91ZC5zcHJ5a2VyLnRveXM.nrve_aGXp_Ow667CgFHZiDjVfik_a_WSqGK5Ls_PhIQ',
        'BR' => 'pub.v2.8216083088630330.aHR0cHM6Ly9ici5teXdvcmxkLW1jYS5jbG91ZC5zcHJ5a2VyLnRveXM.6FgBOez3quSqoWhn6-OnMCd3JuPl6u-voku9dA4VF-Q',
        'CO' => 'pub.v2.8216083088630330.aHR0cHM6Ly9jby5teXdvcmxkLW1jYS5jbG91ZC5zcHJ5a2VyLnRveXM.ryimjZ0By99LaHSqKD_wWcWZsV2B1u0zGCLh2Cir17U',
        'MX' => 'pub.v2.8216083088630330.aHR0cHM6Ly9teC5teXdvcmxkLW1jYS5jbG91ZC5zcHJ5a2VyLnRveXM.GVdLlBU1PK2roRO2dKCT727m3CIyqA5POTJJTblT884',
    ],
];

$config[AdyenConstants::SDK_CHECKOUT_ORIGIN_KEY] = $adyenCredentials['ORIGIN_KEYS'][APPLICATION_STORE];
