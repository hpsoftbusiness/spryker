<?php
// config file for overriding by cluster


use Pyz\Shared\Adyen\AdyenConstants;
use Pyz\Shared\Locale\LocaleConstants;
use Pyz\Shared\MyWorldMarketplaceApi\MyWorldMarketplaceApiConstants;

$config[LocaleConstants::COUNTRY_TO_LOCALE_RELATIONS] = [
    'IT' => 'it_IT',
    'PT' => 'pt_PT',
];

//"originKeys": {
//    "https://it.myworld.cloud.spryker.toys": "pub.v2.8216083088630330.aHR0cHM6Ly9pdC5teXdvcmxkLmNsb3VkLnNwcnlrZXIudG95cw._iTtsWftEAhlfXNU-9tNpNxQ1Mht18xJl7tBoQPL8T4",
//    "https://pt.myworld.cloud.spryker.toys": "pub.v2.8216083088630330.aHR0cHM6Ly9wdC5teXdvcmxkLmNsb3VkLnNwcnlrZXIudG95cw.re5QikWa9Uul6Olh2HYootMQsc7ndSkksL3uWBZPX7o"
//}
$adyenCredentials = [
    'ORIGIN_KEYS' => [
        'IT' => 'pub.v2.8216083088630330.aHR0cHM6Ly9pdC5teXdvcmxkLmNsb3VkLnNwcnlrZXIudG95cw._iTtsWftEAhlfXNU-9tNpNxQ1Mht18xJl7tBoQPL8T4',
        'PT' => 'pub.v2.8216083088630330.aHR0cHM6Ly9wdC5teXdvcmxkLmNsb3VkLnNwcnlrZXIudG95cw.re5QikWa9Uul6Olh2HYootMQsc7ndSkksL3uWBZPX7o',
    ],
];

$config[AdyenConstants::SDK_CHECKOUT_ORIGIN_KEY] = $adyenCredentials['ORIGIN_KEYS'][APPLICATION_STORE];

$config[MyWorldMarketplaceApiConstants::DEALER_ID_DEFAULT] = '5AD45F03-6374-4FA6-B2A0-AA3000CCD79E';
