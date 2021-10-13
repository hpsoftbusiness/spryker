<?php
// config file for overriding by cluster


use Pyz\Shared\Adyen\AdyenConstants;
use Pyz\Shared\Country\CountryConstants;
use Pyz\Shared\Locale\LocaleConstants;

$config[CountryConstants::CLUSTER_COUNTRIES] = ['MY', 'HK', 'AU', 'NZ', 'PH', 'TH', 'MO'];
$config[LocaleConstants::COUNTRY_TO_LOCALE_RELATIONS] = [
    'MY' => 'en_US',
    'HK' => 'en_US',
    'AU' => 'en_US',
    'NZ' => 'en_US',
    'PH' => 'en_US',
    'TH' => 'en_US',
    'MO' => 'en_US',
];
//"originKeys": {
//    "https://yves.ph.myworld.local": "pub.v2.8216083088630330.aHR0cHM6Ly95dmVzLnBoLm15d29ybGQubG9jYWw.ig9rTpTGdnunSYs-JFyL2_QCIMBs5qF24ANaN7oP-E4",
//    "https://yves.mo.myworld.local": "pub.v2.8216083088630330.aHR0cHM6Ly95dmVzLm1vLm15d29ybGQubG9jYWw.tnQSBLuFG1P1VTvEJIPx9EJACQYp4z2-xS5zywlCv0Q",
//    "https://yves.au.myworld.local": "pub.v2.8216083088630330.aHR0cHM6Ly95dmVzLmF1Lm15d29ybGQubG9jYWw.FOUTbdLGipTPAn8tQoQLeRw5noUtYJ-U-Tp7HCenF0M",
//    "https://yves.my.myworld.local": "pub.v2.8216083088630330.aHR0cHM6Ly95dmVzLm15Lm15d29ybGQubG9jYWw.e_buqW1cY8wuDXOtrR8TB9pvW8I8LkqK1KEJkAdk4XY",
//    "https://yves.th.myworld.local": "pub.v2.8216083088630330.aHR0cHM6Ly95dmVzLnRoLm15d29ybGQubG9jYWw.SyKYKGHf6ZwcpN3cZ9wxqlTNSiKe9bXFKjIkHcBFw4k",
//    "https://yves.nz.myworld.local": "pub.v2.8216083088630330.aHR0cHM6Ly95dmVzLm56Lm15d29ybGQubG9jYWw.4ixW-Bf3xMyZGzDVrIInrVE2nEwXm6TWb1gJq2q3oEo",
//    "https://yves.hk.myworld.local": "pub.v2.8216083088630330.aHR0cHM6Ly95dmVzLmhrLm15d29ybGQubG9jYWw.hAF33CBIPpqIPEFa9hG9BJhp7CeRCGEuCz00fow373A"
//    }
$adyenCredentials = [
    'ORIGIN_KEYS' => [
        'MY' => 'pub.v2.8216083088630330.aHR0cHM6Ly95dmVzLm15Lm15d29ybGQubG9jYWw.e_buqW1cY8wuDXOtrR8TB9pvW8I8LkqK1KEJkAdk4XY',
        'HK' => 'pub.v2.8216083088630330.aHR0cHM6Ly95dmVzLmhrLm15d29ybGQubG9jYWw.hAF33CBIPpqIPEFa9hG9BJhp7CeRCGEuCz00fow373A',
        'AU' => 'pub.v2.8216083088630330.aHR0cHM6Ly95dmVzLmF1Lm15d29ybGQubG9jYWw.FOUTbdLGipTPAn8tQoQLeRw5noUtYJ-U-Tp7HCenF0M',
        'NZ' => 'pub.v2.8216083088630330.aHR0cHM6Ly95dmVzLm56Lm15d29ybGQubG9jYWw.4ixW-Bf3xMyZGzDVrIInrVE2nEwXm6TWb1gJq2q3oEo',
        'PH' => 'pub.v2.8216083088630330.aHR0cHM6Ly95dmVzLnBoLm15d29ybGQubG9jYWw.ig9rTpTGdnunSYs-JFyL2_QCIMBs5qF24ANaN7oP-E4',
        'TH' => 'pub.v2.8216083088630330.aHR0cHM6Ly95dmVzLnRoLm15d29ybGQubG9jYWw.SyKYKGHf6ZwcpN3cZ9wxqlTNSiKe9bXFKjIkHcBFw4k',
        'MO' => 'pub.v2.8216083088630330.aHR0cHM6Ly95dmVzLm1vLm15d29ybGQubG9jYWw.tnQSBLuFG1P1VTvEJIPx9EJACQYp4z2-xS5zywlCv0Q',
    ],
];

$config[AdyenConstants::SDK_CHECKOUT_ORIGIN_KEY] = $adyenCredentials['ORIGIN_KEYS'][APPLICATION_STORE];
