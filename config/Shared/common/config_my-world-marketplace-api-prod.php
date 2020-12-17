<?php

use Pyz\Shared\MyWorldMarketplaceApi\MyWorldMarketplaceApiConstants;

// ----------------------------------------------------------------------------
// ---------------------- MyWorld Marketplace API -----------------------------
// ----------------------------------------------------------------------------

// >>> MyWorld Marketplace API

$config[MyWorldMarketplaceApiConstants::API_URL] = 'https://marketplace-gateway.myworldwebservices.com';
$config[MyWorldMarketplaceApiConstants::TOKEN_URL] = 'https://id.cashbackworld.com/oauth/token';
$config[MyWorldMarketplaceApiConstants::CLIENT_ID] = getenv('MY_WORLD_MARKETPLACE_API_CLIENT_ID') ?: '';
$config[MyWorldMarketplaceApiConstants::CLIENT_SECRET] = getenv('MY_WORLD_MARKETPLACE_API_CLIENT_SECRET') ?: '';
$config[MyWorldMarketplaceApiConstants::USER_AGENT] = 'Spryker/202009.0';
$config[MyWorldMarketplaceApiConstants::DEALER_ID] = getenv('MY_WORLD_MARKETPLACE_API_DEALER_ID') ?: '';
