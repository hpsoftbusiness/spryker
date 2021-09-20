<?php
// config file for overriding by cluster


use Pyz\Shared\Adyen\AdyenConstants;
use Pyz\Shared\Locale\LocaleConstants;
use Pyz\Shared\MyWorldMarketplaceApi\MyWorldMarketplaceApiConstants;

$config[LocaleConstants::COUNTRY_TO_LOCALE_RELATIONS] = [
    'HU' => 'hu_HU',
    'RO' => 'ro_RO',
    'MD' => 'ro_MD',
];
//"originKeys": {
//    "https://yves.hu.myworld.local": "pub.v2.8216083088630330.aHR0cHM6Ly95dmVzLmh1Lm15d29ybGQubG9jYWw.KdbEBg2XP1H7nlCh03Buv-RPjYAIJ_gOIqTaNnxvicE",
//    "https://yves.ro.myworld.local": "pub.v2.8216083088630330.aHR0cHM6Ly95dmVzLnJvLm15d29ybGQubG9jYWw.0n4So8aImFQLKeNb4NCEQQqgHMqBMXRk5kaViYK34Jw",
//    "https://yves.md.myworld.local": "pub.v2.8216083088630330.aHR0cHM6Ly95dmVzLm1kLm15d29ybGQubG9jYWw.BKD-ARen3orM0kmOgpvaTYLYIUZ2jUWIXphYN6QdECQ"
//    }
$adyenCredentials = [
    'ORIGIN_KEYS' => [
        'HU' => 'pub.v2.8216083088630330.aHR0cHM6Ly95dmVzLmh1Lm15d29ybGQubG9jYWw.KdbEBg2XP1H7nlCh03Buv-RPjYAIJ_gOIqTaNnxvicE',
        'RO' => 'pub.v2.8216083088630330.aHR0cHM6Ly95dmVzLnJvLm15d29ybGQubG9jYWw.0n4So8aImFQLKeNb4NCEQQqgHMqBMXRk5kaViYK34Jw',
        'MD' => 'pub.v2.8216083088630330.aHR0cHM6Ly95dmVzLm1kLm15d29ybGQubG9jYWw.BKD-ARen3orM0kmOgpvaTYLYIUZ2jUWIXphYN6QdECQ',
    ],
];

$config[AdyenConstants::SDK_CHECKOUT_ORIGIN_KEY] = $adyenCredentials['ORIGIN_KEYS'][APPLICATION_STORE];

$config[MyWorldMarketplaceApiConstants::DEALER_ID_DEFAULT] = '5E39BA6C-48BC-4F6D-B6AB-AAB000CD695E';
