<?php
// config file for overriding by cluster


use Pyz\Shared\Adyen\AdyenConstants;
use Pyz\Shared\Locale\LocaleConstants;
use Pyz\Shared\MyWorldMarketplaceApi\MyWorldMarketplaceApiConstants;

$config[LocaleConstants::COUNTRY_TO_LOCALE_RELATIONS] = [
    'PL' => 'pl_PL',
    'RO' => 'ro_RO',
    'SK' => 'sk_SK',
    'SI' => 'sl_SI',
];

//"originKeys": {
//    "https://yves.sl.myworld.local": "pub.v2.8216083088630330.aHR0cHM6Ly95dmVzLnNsLm15d29ybGQubG9jYWw.ijpAD2dZQgwjyzscOIDNh_jcpCEZ-zlUcMhlVGNu4CU",
//    "https://yves.ro.myworld.local": "pub.v2.8216083088630330.aHR0cHM6Ly95dmVzLnJvLm15d29ybGQubG9jYWw.0n4So8aImFQLKeNb4NCEQQqgHMqBMXRk5kaViYK34Jw",
//    "https://yves.pl.myworld.local": "pub.v2.8216083088630330.aHR0cHM6Ly95dmVzLnBsLm15d29ybGQubG9jYWw.cdJ-tDOyXDbQEN07IhyAihosuHZo5wDSZM9l9z8XRYQ",
//    "https://yves.sk.myworld.local": "pub.v2.8216083088630330.aHR0cHM6Ly95dmVzLnNrLm15d29ybGQubG9jYWw.1p_N7z3WBHS1wNNa915j2jOirXIy_4CAm5KgczmZ_EE"
//}

$adyenCredentials = [
    'ORIGIN_KEYS' => [
        'PL' => 'pub.v2.8216083088630330.aHR0cHM6Ly95dmVzLnBsLm15d29ybGQubG9jYWw.cdJ-tDOyXDbQEN07IhyAihosuHZo5wDSZM9l9z8XRYQ',
        'RO' => 'pub.v2.8216083088630330.aHR0cHM6Ly95dmVzLnJvLm15d29ybGQubG9jYWw.0n4So8aImFQLKeNb4NCEQQqgHMqBMXRk5kaViYK34Jw',
        'SK' => 'pub.v2.8216083088630330.aHR0cHM6Ly95dmVzLnNrLm15d29ybGQubG9jYWw.1p_N7z3WBHS1wNNa915j2jOirXIy_4CAm5KgczmZ_EE',
        'SL' => 'pub.v2.8216083088630330.aHR0cHM6Ly95dmVzLnNsLm15d29ybGQubG9jYWw.ijpAD2dZQgwjyzscOIDNh_jcpCEZ-zlUcMhlVGNu4CU',
    ],
];

$config[AdyenConstants::SDK_CHECKOUT_ORIGIN_KEY] = $adyenCredentials['ORIGIN_KEYS'][APPLICATION_STORE];

$config[MyWorldMarketplaceApiConstants::DEALER_ID_DEFAULT] = 'B6AFC7E1-9410-4E82-AA43-AA3000C95203';
