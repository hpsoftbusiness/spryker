<?php
// config file for overriding by cluster


use Pyz\Shared\Adyen\AdyenConstants;
use Pyz\Shared\Locale\LocaleConstants;
use Pyz\Shared\MyWorldMarketplaceApi\MyWorldMarketplaceApiConstants;

$config[LocaleConstants::COUNTRY_TO_LOCALE_RELATIONS] = [
    'AT' => 'de_DE',
    'DE' => 'de_DE',
];

//"originKeys": {
//   "https://de.myworld.cloud.spryker.toys": "pub.v2.8216083088630330.aHR0cHM6Ly9kZS5teXdvcmxkLmNsb3VkLnNwcnlrZXIudG95cw.BQ-jvFZDmr0oKxLtLtmzehyS4wFjamkyCk-kz4YKbUo",
//   "https://at.myworld.cloud.spryker.toys": "pub.v2.8216083088630330.aHR0cHM6Ly9hdC5teXdvcmxkLmNsb3VkLnNwcnlrZXIudG95cw.w2hDN2ox3y4JqvLXm1Jofq0TsCajcmCiOl7u_m0QStU"
//}
$adyenCredentials = [
    'ORIGIN_KEYS' => [
        'AT' => 'pub.v2.8216083088630330.aHR0cHM6Ly95dmVzLmF0Lm15d29ybGQubG9jYWw.sBKoMF2gveV7BiYyI1aX2Z3sM1i0D_XYi-nMJz815Es',
        'DE' => 'pub.v2.8216083088630330.aHR0cHM6Ly9kZS5teXdvcmxkLmNsb3VkLnNwcnlrZXIudG95cw.BQ-jvFZDmr0oKxLtLtmzehyS4wFjamkyCk-kz4YKbUo',
    ],
];

$config[AdyenConstants::SDK_CHECKOUT_ORIGIN_KEY] = $adyenCredentials['ORIGIN_KEYS'][APPLICATION_STORE];

$config[MyWorldMarketplaceApiConstants::DEALER_ID_DEFAULT] = 'BA3E82A7-BBC4-4874-A383-AA3100985CC9';
