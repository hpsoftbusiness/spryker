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
//    "https://yves.pt.myworld.local": "pub.v2.8216083088630330.aHR0cHM6Ly95dmVzLnB0Lm15d29ybGQubG9jYWw.uNF1n_otTMcQQatevrKfDeGHIf8A81PLICDF7hnLPoY",
//    "https://yves.it.myworld.local": "pub.v2.8216083088630330.aHR0cHM6Ly95dmVzLml0Lm15d29ybGQubG9jYWw.RxdX29Fu1OBv3pyG3AiVP1pdj2CB3g1sWzKG_0s6yCI"
//}
$adyenCredentials = [
    'ORIGIN_KEYS' => [
        'IT' => 'pub.v2.8216083088630330.aHR0cHM6Ly95dmVzLml0Lm15d29ybGQubG9jYWw.RxdX29Fu1OBv3pyG3AiVP1pdj2CB3g1sWzKG_0s6yCI',
        'PT' => 'pub.v2.8216083088630330.aHR0cHM6Ly95dmVzLnB0Lm15d29ybGQubG9jYWw.uNF1n_otTMcQQatevrKfDeGHIf8A81PLICDF7hnLPoY',
    ],
];

$config[AdyenConstants::SDK_CHECKOUT_ORIGIN_KEY] = $adyenCredentials['ORIGIN_KEYS'][APPLICATION_STORE];

$config[MyWorldMarketplaceApiConstants::DEALER_ID_DEFAULT] = '5AD45F03-6374-4FA6-B2A0-AA3000CCD79E';
