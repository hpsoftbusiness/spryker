<?php
// config file for overriding by cluster


use Pyz\Shared\Adyen\AdyenConstants;
use Pyz\Shared\Locale\LocaleConstants;

$config[LocaleConstants::COUNTRY_TO_LOCALE_RELATIONS] = [
    'CA' => 'fr_CA',
    'BR' => 'pt_BR',
    'CO' => 'es_CO',
    'MX' => 'es_MX',
];
//"originKeys": {
//    "https://yves.br.myworld.local": "pub.v2.8216083088630330.aHR0cHM6Ly95dmVzLmJyLm15d29ybGQubG9jYWw.VJLkxk5m2FHoT6ZTetXjVdcYQnNaxoXx0-w3CByB92I",
//    "https://yves.co.myworld.local": "pub.v2.8216083088630330.aHR0cHM6Ly95dmVzLmNvLm15d29ybGQubG9jYWw.H_1QwJ6acC9iK8k-WqtASm-uktTKQj5zb0SUyaJ4WtE",
//    "https://yves.mx.myworld.local": "pub.v2.8216083088630330.aHR0cHM6Ly95dmVzLm14Lm15d29ybGQubG9jYWw.m8V9NlE-Hh49WpmRqAA8TJVlX65QwuOnyOpd_Ivf1nk",
//    "https://yves.ca.myworld.local": "pub.v2.8216083088630330.aHR0cHM6Ly95dmVzLmNhLm15d29ybGQubG9jYWw.5l1jboIu1PwthdtoUeBpKLO7TF9Bzhvfuvi_QX_Kd7U"
//    }
$adyenCredentials = [
    'ORIGIN_KEYS' => [
        'CA' => 'pub.v2.8216083088630330.aHR0cHM6Ly95dmVzLmNhLm15d29ybGQubG9jYWw.5l1jboIu1PwthdtoUeBpKLO7TF9Bzhvfuvi_QX_Kd7U',
        'BR' => 'pub.v2.8216083088630330.aHR0cHM6Ly95dmVzLmJyLm15d29ybGQubG9jYWw.VJLkxk5m2FHoT6ZTetXjVdcYQnNaxoXx0-w3CByB92I',
        'CO' => 'pub.v2.8216083088630330.aHR0cHM6Ly95dmVzLmNvLm15d29ybGQubG9jYWw.H_1QwJ6acC9iK8k-WqtASm-uktTKQj5zb0SUyaJ4WtE',
        'MX' => 'pub.v2.8216083088630330.aHR0cHM6Ly95dmVzLm14Lm15d29ybGQubG9jYWw.m8V9NlE-Hh49WpmRqAA8TJVlX65QwuOnyOpd_Ivf1nk',
    ],
];

$config[AdyenConstants::SDK_CHECKOUT_ORIGIN_KEY] = $adyenCredentials['ORIGIN_KEYS'][APPLICATION_STORE];
