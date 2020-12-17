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
$config[MyWorldMarketplaceApiConstants::DEALER_ID] = getenv('MY_WORLD_MARKETPLACE_API_DEALER_ID') ?: '01aa226e-47ae-4c87-9be1-aa30008987e8';
