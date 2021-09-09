<?php
// config file for overriding by cluster


use Pyz\Shared\Adyen\AdyenConstants;
use Pyz\Shared\Locale\LocaleConstants;
use Pyz\Shared\MyWorldMarketplaceApi\MyWorldMarketplaceApiConstants;

$config[LocaleConstants::COUNTRY_TO_LOCALE_RELATIONS] = [
    'NO' => 'nn_NO',
    'FI' => 'fi_FI',
    'DK' => 'da_DK',
    'SE' => 'sv_SE',
];
//"originKeys": {
//    "https://yves.fi.myworld.local": "pub.v2.8216083088630330.aHR0cHM6Ly95dmVzLmZpLm15d29ybGQubG9jYWw.G_j8xZJxufm3E92I-wXZW2fphYUa1NOoHHgK48TFUnA",
//    "https://yves.no.myworld.local": "pub.v2.8216083088630330.aHR0cHM6Ly95dmVzLm5vLm15d29ybGQubG9jYWw.1xxQdViYRpbHwd1CW6kC-KEajxiM4d7a8rAfiMTzslE",
//    "https://yves.se.myworld.local": "pub.v2.8216083088630330.aHR0cHM6Ly95dmVzLnNlLm15d29ybGQubG9jYWw.4dEYOcii01AmLbFub10jgrKNqjJJDgcT4Vh2-iyr7dM",
//    "https://yves.dk.myworld.local": "pub.v2.8216083088630330.aHR0cHM6Ly95dmVzLmRrLm15d29ybGQubG9jYWw.y8j8x2aeIHu22B2Fxbf3wOPJHqyVk0MQynqIsLD-aMU"
//    }
$adyenCredentials = [
    'ORIGIN_KEYS' => [
        'NO' => 'pub.v2.8216083088630330.aHR0cHM6Ly95dmVzLmZpLm15d29ybGQubG9jYWw.G_j8xZJxufm3E92I-wXZW2fphYUa1NOoHHgK48TFUnA',
        'FI' => 'pub.v2.8216083088630330.aHR0cHM6Ly95dmVzLm5vLm15d29ybGQubG9jYWw.1xxQdViYRpbHwd1CW6kC-KEajxiM4d7a8rAfiMTzslE',
        'DK' => 'pub.v2.8216083088630330.aHR0cHM6Ly95dmVzLnNlLm15d29ybGQubG9jYWw.4dEYOcii01AmLbFub10jgrKNqjJJDgcT4Vh2-iyr7dM',
        'SE' => 'pub.v2.8216083088630330.aHR0cHM6Ly95dmVzLmRrLm15d29ybGQubG9jYWw.y8j8x2aeIHu22B2Fxbf3wOPJHqyVk0MQynqIsLD-aMU',
    ],
];

$config[AdyenConstants::SDK_CHECKOUT_ORIGIN_KEY] = $adyenCredentials['ORIGIN_KEYS'][APPLICATION_STORE];

$config[MyWorldMarketplaceApiConstants::DEALER_ID_DEFAULT] = '8F8D1A16-E266-4906-9528-AA310068B044';
