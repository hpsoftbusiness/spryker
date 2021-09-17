<?php
// config file for overriding by cluster


use Pyz\Shared\Adyen\AdyenConstants;
use Pyz\Shared\Locale\LocaleConstants;
use Pyz\Shared\MyWorldMarketplaceApi\MyWorldMarketplaceApiConstants;

$config[LocaleConstants::COUNTRY_TO_LOCALE_RELATIONS] = [
    'IT' => 'it_IT',
    'FR' => 'fr_FR',
    'GR' => 'el_GR',
];

//"originKeys": {
//    "https://yves.gr.myworld.local": "pub.v2.8216083088630330.aHR0cHM6Ly95dmVzLmdyLm15d29ybGQubG9jYWw.gudHJmUR3hAcWgwiLTsZ7k6t4UEhOGmp9NY0EyV_hrY",
//    "https://yves.it.myworld.local": "pub.v2.8216083088630330.aHR0cHM6Ly95dmVzLml0Lm15d29ybGQubG9jYWw.RxdX29Fu1OBv3pyG3AiVP1pdj2CB3g1sWzKG_0s6yCI",
//    "https://yves.fr.myworld.local": "pub.v2.8216083088630330.aHR0cHM6Ly95dmVzLmZyLm15d29ybGQubG9jYWw.enwrMUrGhidseq8f5Dq2jvS4NdXaq5KwLeFJYnXWabA"
//    }
$adyenCredentials = [
    'ORIGIN_KEYS' => [
        'IT' => 'pub.v2.8216083088630330.aHR0cHM6Ly95dmVzLml0Lm15d29ybGQubG9jYWw.RxdX29Fu1OBv3pyG3AiVP1pdj2CB3g1sWzKG_0s6yCI',
        'FR' => 'pub.v2.8216083088630330.aHR0cHM6Ly95dmVzLmZyLm15d29ybGQubG9jYWw.enwrMUrGhidseq8f5Dq2jvS4NdXaq5KwLeFJYnXWabA',
        'GR' => 'pub.v2.8216083088630330.aHR0cHM6Ly95dmVzLmdyLm15d29ybGQubG9jYWw.gudHJmUR3hAcWgwiLTsZ7k6t4UEhOGmp9NY0EyV_hrY',
    ],
];

$config[AdyenConstants::SDK_CHECKOUT_ORIGIN_KEY] = $adyenCredentials['ORIGIN_KEYS'][APPLICATION_STORE];

$config[MyWorldMarketplaceApiConstants::DEALER_ID_DEFAULT] = '5AD45F03-6374-4FA6-B2A0-AA3000CCD79E';
