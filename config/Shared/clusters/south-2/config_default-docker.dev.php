<?php
// config file for overriding by cluster


use Pyz\Shared\Adyen\AdyenConstants;
use Pyz\Shared\Locale\LocaleConstants;
use Pyz\Shared\MyWorldMarketplaceApi\MyWorldMarketplaceApiConstants;

$config[LocaleConstants::COUNTRY_TO_LOCALE_RELATIONS] = [
    'ES' => 'es_ES',
    'PT' => 'pt_PT',
];

//"originKeys": {
//    "https://yves.pt.myworld.local": "pub.v2.8216083088630330.aHR0cHM6Ly95dmVzLnB0Lm15d29ybGQubG9jYWw.uNF1n_otTMcQQatevrKfDeGHIf8A81PLICDF7hnLPoY",
//    "https://yves.es.myworld.local": "pub.v2.8216083088630330.aHR0cHM6Ly95dmVzLmVzLm15d29ybGQubG9jYWw.jNBstbM0QmXHUwpXITND9-QPBMfvFWMGJPPeMXCHuo4"
//    }
$adyenCredentials = [
    'ORIGIN_KEYS' => [
        'ES' => 'pub.v2.8216083088630330.aHR0cHM6Ly95dmVzLnB0Lm15d29ybGQubG9jYWw.uNF1n_otTMcQQatevrKfDeGHIf8A81PLICDF7hnLPoY',
        'PT' => 'pub.v2.8216083088630330.aHR0cHM6Ly95dmVzLmVzLm15d29ybGQubG9jYWw.jNBstbM0QmXHUwpXITND9-QPBMfvFWMGJPPeMXCHuo4',
    ],
];

$config[AdyenConstants::SDK_CHECKOUT_ORIGIN_KEY] = $adyenCredentials['ORIGIN_KEYS'][APPLICATION_STORE];

$config[MyWorldMarketplaceApiConstants::DEALER_ID_DEFAULT] = '58FBDDD4-CD67-4F9E-96A1-AAC000564B0E';
