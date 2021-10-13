<?php
// config file for overriding by cluster


use Pyz\Shared\Adyen\AdyenConstants;
use Pyz\Shared\Country\CountryConstants;
use Pyz\Shared\Locale\LocaleConstants;
use Pyz\Shared\MyWorldMarketplaceApi\MyWorldMarketplaceApiConstants;

$config[CountryConstants::CLUSTER_COUNTRIES] = ['IT', 'FR', 'GR', 'MT', 'MC', 'LU', 'PT', 'CY', 'TR'];
$config[LocaleConstants::COUNTRY_TO_LOCALE_RELATIONS] = [
    'IT' => 'it_IT',
    'FR' => 'fr_FR',
    'GR' => 'el_GR',
];

//"originKeys": {
//    "https://it.myworld-mca.cloud.spryker.toys": "pub.v2.8216083088630330.aHR0cHM6Ly9pdC5teXdvcmxkLW1jYS5jbG91ZC5zcHJ5a2VyLnRveXM.DiJqo1SBEt1tsdcT-Y6kPxuQv-8ibFmEudJ9vCVdhj0",
//    "https://gr.myworld-mca.cloud.spryker.toys": "pub.v2.8216083088630330.aHR0cHM6Ly9nci5teXdvcmxkLW1jYS5jbG91ZC5zcHJ5a2VyLnRveXM._P5crRCCUxzRa-54fkeWcqO_d6qUUa1oQHke4hgqVWE",
//    "https://fr.myworld-mca.cloud.spryker.toys": "pub.v2.8216083088630330.aHR0cHM6Ly9mci5teXdvcmxkLW1jYS5jbG91ZC5zcHJ5a2VyLnRveXM.-N5hwGeqHzX_vOmNLn6khGtuuc7bF3-UuUsk3Zs6A1M"
//    }
$adyenCredentials = [
    'ORIGIN_KEYS' => [
        'IT' => 'pub.v2.8216083088630330.aHR0cHM6Ly9pdC5teXdvcmxkLW1jYS5jbG91ZC5zcHJ5a2VyLnRveXM.DiJqo1SBEt1tsdcT-Y6kPxuQv-8ibFmEudJ9vCVdhj0',
        'FR' => 'pub.v2.8216083088630330.aHR0cHM6Ly9mci5teXdvcmxkLW1jYS5jbG91ZC5zcHJ5a2VyLnRveXM.-N5hwGeqHzX_vOmNLn6khGtuuc7bF3-UuUsk3Zs6A1M',
        'GR' => 'pub.v2.8216083088630330.aHR0cHM6Ly9nci5teXdvcmxkLW1jYS5jbG91ZC5zcHJ5a2VyLnRveXM._P5crRCCUxzRa-54fkeWcqO_d6qUUa1oQHke4hgqVWE',
    ],
];

$config[AdyenConstants::SDK_CHECKOUT_ORIGIN_KEY] = $adyenCredentials['ORIGIN_KEYS'][APPLICATION_STORE];

$config[MyWorldMarketplaceApiConstants::DEALER_ID_DEFAULT] = '5AD45F03-6374-4FA6-B2A0-AA3000CCD79E';
