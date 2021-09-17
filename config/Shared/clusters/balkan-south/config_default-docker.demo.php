<?php
// config file for overriding by cluster


use Pyz\Shared\Adyen\AdyenConstants;
use Pyz\Shared\Locale\LocaleConstants;

$config[LocaleConstants::COUNTRY_TO_LOCALE_RELATIONS] = [
    'BG' => 'bg_BG',
    'MK' => 'mk_MK',
    'AL' => 'sq_AL',
    'XK' => 'sr_RS',
];
//"originKeys": {
//    "https://bg.myworld-mca.cloud.spryker.toys": "pub.v2.8216083088630330.aHR0cHM6Ly9iZy5teXdvcmxkLW1jYS5jbG91ZC5zcHJ5a2VyLnRveXM.5z4YbxtPiyjpZBHuj9wCGabhfm4VOXds6DD2vQuvF-k",
//    "https://al.myworld-mca.cloud.spryker.toys": "pub.v2.8216083088630330.aHR0cHM6Ly9hbC5teXdvcmxkLW1jYS5jbG91ZC5zcHJ5a2VyLnRveXM.X_zN0HfNwlgMCC79GssAPFlQXJ0pNNDFPLLgwnbqroo",
//    "https://mk.myworld-mca.cloud.spryker.toys": "pub.v2.8216083088630330.aHR0cHM6Ly9tay5teXdvcmxkLW1jYS5jbG91ZC5zcHJ5a2VyLnRveXM.-akKEtHUXrn5TmtYTpXf9RO6mZSkICRy1mDh-5t0M2U",
//    "https://xk.myworld-mca.cloud.spryker.toys": "pub.v2.8216083088630330.aHR0cHM6Ly94ay5teXdvcmxkLW1jYS5jbG91ZC5zcHJ5a2VyLnRveXM.dcPAKfXzpJ9j9g677wE6h2HK7bYwSkXvVuL_HtIaTlg"
//    }
$adyenCredentials = [
    'ORIGIN_KEYS' => [
        'BG' => 'pub.v2.8216083088630330.aHR0cHM6Ly9iZy5teXdvcmxkLW1jYS5jbG91ZC5zcHJ5a2VyLnRveXM.5z4YbxtPiyjpZBHuj9wCGabhfm4VOXds6DD2vQuvF-k',
        'MK' => 'pub.v2.8216083088630330.aHR0cHM6Ly9tay5teXdvcmxkLW1jYS5jbG91ZC5zcHJ5a2VyLnRveXM.-akKEtHUXrn5TmtYTpXf9RO6mZSkICRy1mDh-5t0M2U',
        'AL' => 'pub.v2.8216083088630330.aHR0cHM6Ly9hbC5teXdvcmxkLW1jYS5jbG91ZC5zcHJ5a2VyLnRveXM.X_zN0HfNwlgMCC79GssAPFlQXJ0pNNDFPLLgwnbqroo',
        'XK' => 'pub.v2.8216083088630330.aHR0cHM6Ly94ay5teXdvcmxkLW1jYS5jbG91ZC5zcHJ5a2VyLnRveXM.dcPAKfXzpJ9j9g677wE6h2HK7bYwSkXvVuL_HtIaTlg',
    ],
];

$config[AdyenConstants::SDK_CHECKOUT_ORIGIN_KEY] = $adyenCredentials['ORIGIN_KEYS'][APPLICATION_STORE];
