<?php
// config file for overriding by cluster


use Pyz\Shared\Adyen\AdyenConstants;
use Pyz\Shared\Locale\LocaleConstants;

$config[LocaleConstants::COUNTRY_TO_LOCALE_RELATIONS] = [
    'BY' => 'be_BY',
    'UA' => 'uk_UA',
    'RU' => 'ru_RU',
];
//"originKeys": {
//    "https://yves.ru.myworld.local": "pub.v2.8216083088630330.aHR0cHM6Ly95dmVzLnJ1Lm15d29ybGQubG9jYWw.PnlLJ79qwiQoHR0XxaB8M7ItUxlwgcdL6ptILWgK_x8",
//    "https://yves.by.myworld.local": "pub.v2.8216083088630330.aHR0cHM6Ly95dmVzLmJ5Lm15d29ybGQubG9jYWw.fskKCEIRwn4h0IxCW4RUgUgceds_nzAaNBjpSjLPf0o",
//    "https://yves.ua.myworld.local": "pub.v2.8216083088630330.aHR0cHM6Ly95dmVzLnVhLm15d29ybGQubG9jYWw.HQN4iFzmiYdGIW_du6uUkBIa2HNsyXtoCzFQaTL4u0s"
//    }
$adyenCredentials = [
    'ORIGIN_KEYS' => [
        'BY' => 'pub.v2.8216083088630330.aHR0cHM6Ly95dmVzLmJ5Lm15d29ybGQubG9jYWw.fskKCEIRwn4h0IxCW4RUgUgceds_nzAaNBjpSjLPf0o',
        'UA' => 'pub.v2.8216083088630330.aHR0cHM6Ly95dmVzLnVhLm15d29ybGQubG9jYWw.HQN4iFzmiYdGIW_du6uUkBIa2HNsyXtoCzFQaTL4u0s',
        'RU' => 'pub.v2.8216083088630330.aHR0cHM6Ly95dmVzLnJ1Lm15d29ybGQubG9jYWw.PnlLJ79qwiQoHR0XxaB8M7ItUxlwgcdL6ptILWgK_x8',
    ],
];

$config[AdyenConstants::SDK_CHECKOUT_ORIGIN_KEY] = $adyenCredentials['ORIGIN_KEYS'][APPLICATION_STORE];
