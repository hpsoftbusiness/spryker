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
//    "https://by.myworld.cloud.spryker.toys": "pub.v2.8216083088630330.aHR0cHM6Ly9ieS5teXdvcmxkLmNsb3VkLnNwcnlrZXIudG95cw.KsMfRMrhmTzzb_ub0O-dcNxbPNANW9JjMP4M0GDNuL8",
//    "https://ua.myworld.cloud.spryker.toys": "pub.v2.8216083088630330.aHR0cHM6Ly91YS5teXdvcmxkLmNsb3VkLnNwcnlrZXIudG95cw.sRySIRT_eCcGQ4D_YkALIpKdOrbbsvqSMrOtsSmOWW0",
//    "https://ru.myworld.cloud.spryker.toys": "pub.v2.8216083088630330.aHR0cHM6Ly9ydS5teXdvcmxkLmNsb3VkLnNwcnlrZXIudG95cw.rgJcuG4t6j_9KNFEeQ6HN08ZOZZcNL2p-HGoW446L-g"
//    }
$adyenCredentials = [
    'ORIGIN_KEYS' => [
        'BY' => 'pub.v2.8216083088630330.aHR0cHM6Ly9ieS5teXdvcmxkLmNsb3VkLnNwcnlrZXIudG95cw.KsMfRMrhmTzzb_ub0O-dcNxbPNANW9JjMP4M0GDNuL8',
        'UA' => 'pub.v2.8216083088630330.aHR0cHM6Ly91YS5teXdvcmxkLmNsb3VkLnNwcnlrZXIudG95cw.sRySIRT_eCcGQ4D_YkALIpKdOrbbsvqSMrOtsSmOWW0',
        'RU' => 'pub.v2.8216083088630330.aHR0cHM6Ly9ydS5teXdvcmxkLmNsb3VkLnNwcnlrZXIudG95cw.rgJcuG4t6j_9KNFEeQ6HN08ZOZZcNL2p-HGoW446L-g',
    ],
];

$config[AdyenConstants::SDK_CHECKOUT_ORIGIN_KEY] = $adyenCredentials['ORIGIN_KEYS'][APPLICATION_STORE];
