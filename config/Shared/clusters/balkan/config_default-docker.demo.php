<?php
// config file for overriding by cluster


use Pyz\Shared\Adyen\AdyenConstants;
use Pyz\Shared\Locale\LocaleConstants;

$config[LocaleConstants::COUNTRY_TO_LOCALE_RELATIONS] = [
    'SI' => 'sl_SI',
    'HR' => 'hr_HR',
    'BA' => 'bs_BA',
    'RS' => 'sr_RS',
    'ME' => 'sr_ME',
];
//"originKeys": {
//    "https://ba.myworld-mca.cloud.spryker.toys": "pub.v2.8216083088630330.aHR0cHM6Ly9iYS5teXdvcmxkLW1jYS5jbG91ZC5zcHJ5a2VyLnRveXM.dPqIpx2bbdHvhpSAOidm0yVD6aJzMIdG3MiI9IEFfRw",
//    "https://me.myworld-mca.cloud.spryker.toys": "pub.v2.8216083088630330.aHR0cHM6Ly9tZS5teXdvcmxkLW1jYS5jbG91ZC5zcHJ5a2VyLnRveXM.IwFLSnh7O8C_kd3ewakHcyqZ6tuIwr9uQIebzJiqWkc",
//    "https://si.myworld-mca.cloud.spryker.toys": "pub.v2.8216083088630330.aHR0cHM6Ly9zaS5teXdvcmxkLW1jYS5jbG91ZC5zcHJ5a2VyLnRveXM.058H_Rpdbf_d-064QYUlgbRYDxvoMjayoe6HH3AgDdk",
//    "https://rs.myworld-mca.cloud.spryker.toys": "pub.v2.8216083088630330.aHR0cHM6Ly9ycy5teXdvcmxkLW1jYS5jbG91ZC5zcHJ5a2VyLnRveXM.t7YJ9mXApWbKRMTtDPNnBLwfq4SgM990CtwVC6BLz50",
//    "https://hr.myworld-mca.cloud.spryker.toys": "pub.v2.8216083088630330.aHR0cHM6Ly9oci5teXdvcmxkLW1jYS5jbG91ZC5zcHJ5a2VyLnRveXM.VCUrrtJcNz04vCcgudURBxmeMqtntTJmq8L7JRG2C9w"
//    }
$adyenCredentials = [
    'ORIGIN_KEYS' => [
        'SI' => 'pub.v2.8216083088630330.aHR0cHM6Ly9zaS5teXdvcmxkLW1jYS5jbG91ZC5zcHJ5a2VyLnRveXM.058H_Rpdbf_d-064QYUlgbRYDxvoMjayoe6HH3AgDdk',
        'HR' => 'pub.v2.8216083088630330.aHR0cHM6Ly9oci5teXdvcmxkLW1jYS5jbG91ZC5zcHJ5a2VyLnRveXM.VCUrrtJcNz04vCcgudURBxmeMqtntTJmq8L7JRG2C9w',
        'BA' => 'pub.v2.8216083088630330.aHR0cHM6Ly9iYS5teXdvcmxkLW1jYS5jbG91ZC5zcHJ5a2VyLnRveXM.dPqIpx2bbdHvhpSAOidm0yVD6aJzMIdG3MiI9IEFfRw',
        'RS' => 'pub.v2.8216083088630330.aHR0cHM6Ly9ycy5teXdvcmxkLW1jYS5jbG91ZC5zcHJ5a2VyLnRveXM.t7YJ9mXApWbKRMTtDPNnBLwfq4SgM990CtwVC6BLz50',
        'ME' => 'pub.v2.8216083088630330.aHR0cHM6Ly9tZS5teXdvcmxkLW1jYS5jbG91ZC5zcHJ5a2VyLnRveXM.IwFLSnh7O8C_kd3ewakHcyqZ6tuIwr9uQIebzJiqWkc',
    ],
];

$config[AdyenConstants::SDK_CHECKOUT_ORIGIN_KEY] = $adyenCredentials['ORIGIN_KEYS'][APPLICATION_STORE];
