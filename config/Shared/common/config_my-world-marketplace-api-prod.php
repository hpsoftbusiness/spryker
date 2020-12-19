<?php

use Pyz\Shared\MyWorldMarketplaceApi\MyWorldMarketplaceApiConstants;

// ----------------------------------------------------------------------------
// ---------------------- MyWorld Marketplace API -----------------------------
// ----------------------------------------------------------------------------

// >>> MyWorld Marketplace API

$config[MyWorldMarketplaceApiConstants::API_URL] = getenv('MY_WORLD_MARKETPLACE_API_API_URL') ?: 'https://preprod-marketplace-gateway.myworldwebservices.com';
$config[MyWorldMarketplaceApiConstants::TOKEN_URL] = getenv('MY_WORLD_MARKETPLACE_API_TOKEN_URL') ?: 'https://id-test.cashbackworld.com/trunk/oauth/token';
$config[MyWorldMarketplaceApiConstants::CLIENT_ID] = getenv('MY_WORLD_MARKETPLACE_API_CLIENT_ID') ?: 'spryker_api_at_dev';
$config[MyWorldMarketplaceApiConstants::CLIENT_SECRET] = getenv('MY_WORLD_MARKETPLACE_API_CLIENT_SECRET') ?: 'spryker_api_at_dev';
$config[MyWorldMarketplaceApiConstants::USER_AGENT] = 'Spryker/202009.0';
$config[MyWorldMarketplaceApiConstants::SCOPE] = 'sprykerservice';
$config[MyWorldMarketplaceApiConstants::DEALER_ID_DEFAULT] = 'BA3E82A7-BBC4-4874-A383-AA3100985CC9';
$config[MyWorldMarketplaceApiConstants::DEALER_ID_COUNTRY_MAP] = [
    'AT' => 'BA3E82A7-BBC4-4874-A383-AA3100985CC9',
    'PL' => 'B6AFC7E1-9410-4E82-AA43-AA3000C95203',
    'CH' => '946105EA-A9E3-43D4-BF8E-AA3000CEFDF0',
    'NO' => '8F8D1A16-E266-4906-9528-AA310068B044',
    'SI' => 'EF06C080-96B6-4E9A-900F-AA310069C71E',
    'IT' => '5AD45F03-6374-4FA6-B2A0-AA3000CCD79E',
    'DE' => '99A2CD28-6523-413B-B6D0-AA31008C40DA',
    'CZ' => 'B0F730BC-186A-45AA-A600-AAB00084D1F3',
    'SK' => '338856FD-4D6C-40B5-8117-AAB0008676D1',
    'HU' => '5E39BA6C-48BC-4F6D-B6AB-AAB000CD695E',
    'SE' => 'd7fd22e2-09f8-42bc-bfab-aabf00dfba98',
    'PT' => '126ccf87-0d0e-4093-bacd-aabf00c8fd48',
    'US' => '638cc7c3-afd5-4bcd-9bd1-ac3d008f43bb',
    'MY' => 'a6377ab0-d487-4396-a9a7-ac3f00cfe3c9',
];
