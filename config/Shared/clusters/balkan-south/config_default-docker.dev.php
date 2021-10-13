<?php
// config file for overriding by cluster


use Pyz\Shared\Adyen\AdyenConstants;
use Pyz\Shared\Country\CountryConstants;
use Pyz\Shared\Locale\LocaleConstants;

$config[CountryConstants::CLUSTER_COUNTRIES] = ['BG', 'MK', 'AL', 'XK'];
$config[LocaleConstants::COUNTRY_TO_LOCALE_RELATIONS] = [
    'BG' => 'bg_BG',
    'MK' => 'mk_MK',
    'AL' => 'sq_AL',
    'XK' => 'sr_RS',
];
//"originKeys": {
//    "https://yves.bg.myworld.local": "pub.v2.8216083088630330.aHR0cHM6Ly95dmVzLmJnLm15d29ybGQubG9jYWw.JPz1CrJeZvTbVxJs8iiqKIZ0KUGvzImXb0VQi4feVe8",
//    "https://yves.al.myworld.local": "pub.v2.8216083088630330.aHR0cHM6Ly95dmVzLmFsLm15d29ybGQubG9jYWw.QN8yi4Ub2NqOs9SEV4r0ABjWtlvPtJAO_16qvH_g67s",
//    "https://yves.mk.myworld.local": "pub.v2.8216083088630330.aHR0cHM6Ly95dmVzLm1rLm15d29ybGQubG9jYWw.h-hio7Ad9IlWjp5YZH5AZokuY2nisnq1-dp-0iMLG6g",
//    "https://yves.xk.myworld.local": "pub.v2.8216083088630330.aHR0cHM6Ly95dmVzLnhrLm15d29ybGQubG9jYWw.d5rxwXiO9Mxe2AdRTnJ3jSQCmGwWR6TsGM_GARB6Pbg"
//    }
$adyenCredentials = [
    'ORIGIN_KEYS' => [
        'BG' => 'pub.v2.8216083088630330.aHR0cHM6Ly95dmVzLmJnLm15d29ybGQubG9jYWw.JPz1CrJeZvTbVxJs8iiqKIZ0KUGvzImXb0VQi4feVe8',
        'MK' => 'pub.v2.8216083088630330.aHR0cHM6Ly95dmVzLm1rLm15d29ybGQubG9jYWw.h-hio7Ad9IlWjp5YZH5AZokuY2nisnq1-dp-0iMLG6g',
        'AL' => 'pub.v2.8216083088630330.aHR0cHM6Ly95dmVzLmFsLm15d29ybGQubG9jYWw.QN8yi4Ub2NqOs9SEV4r0ABjWtlvPtJAO_16qvH_g67s',
        'XK' => 'pub.v2.8216083088630330.aHR0cHM6Ly95dmVzLnhrLm15d29ybGQubG9jYWw.d5rxwXiO9Mxe2AdRTnJ3jSQCmGwWR6TsGM_GARB6Pbg',
    ],
];

$config[AdyenConstants::SDK_CHECKOUT_ORIGIN_KEY] = $adyenCredentials['ORIGIN_KEYS'][APPLICATION_STORE];
