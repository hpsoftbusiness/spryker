<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

// ----------------------------------------------------------------------------
// ---------------------- MyWorld Marketplace API -----------------------------
// ----------------------------------------------------------------------------

// >>> MyWorld Marketplace API

use Pyz\Shared\MyWorldMarketplaceApi\MyWorldMarketplaceApiConstants;

$config[MyWorldMarketplaceApiConstants::API_URL] = 'https://preprod-marketplace-gateway.myworldwebservices.com';
$config[MyWorldMarketplaceApiConstants::TOKEN_URL] = 'https://id-test.cashbackworld.com/trunk/oauth/token';
$config[MyWorldMarketplaceApiConstants::CLIENT_ID] = 'spryker_api_at_dev';
$config[MyWorldMarketplaceApiConstants::CLIENT_SECRET] = 'spryker_api_at_dev';
$config[MyWorldMarketplaceApiConstants::USER_AGENT] = 'Spryker/202009.0';
$config[MyWorldMarketplaceApiConstants::DEALER_ID] = '01aa226e-47ae-4c87-9be1-aa30008987e8';

