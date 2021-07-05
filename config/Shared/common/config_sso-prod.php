<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

use Pyz\Shared\Sso\SsoConstants;
use Spryker\Shared\Kernel\KernelConstants;

// ----------------------------------------------------------------------------
// --------------------------- Single Sigh On ---------------------------------
// ----------------------------------------------------------------------------

// >>> SSO
$config[SsoConstants::SSO_LOGIN_ENABLED] = true;

$config[SsoConstants::TOKEN_URL] = getenv('SSO_TOKEN_URL') ? : 'https://id-test.cashbackworld.com/trunk/oauth/token';
$config[SsoConstants::AUTHORIZE_URL] = getenv('SSO_AUTHORIZE_URL') ? : 'https://id-test.cashbackworld.com/trunk/oauth/authorize';
$config[SsoConstants::CUSTOMER_INFORMATION_URL] = getenv('SSO_CUSTOMER_INFORMATION_URL') ? : 'https://preprod-marketplace-gateway.myworldwebservices.com/customers';
$config[SsoConstants::CUSTOMER_ACCOUNT_BALANCE_BY_CURRENCY_URL] = getenv('SSO_CUSTOMER_ACCOUNT_BALANCE_BY_CURRENCY_URL')
    ?: 'https://marketplace-gateway.myworldwebservices.com/customers/{customerID}/accounts/currency/{targetCurrency}';
$config[SsoConstants::LOGIN_CHECK_PATH] = 'login_check';

$config[SsoConstants::RESPONSE_TYPE] = 'code';
$config[SsoConstants::CLIENT_ID] = getenv('SSO_CLIENT_ID');
$config[SsoConstants::CLIENT_SECRET] = getenv('SSO_CLIENT_SECRET');
$config[SsoConstants::USER_AGENT] = 'Spryker/202009.0';
$config[SsoConstants::SCOPE] = 'openid';

$config[SsoConstants::REDIRECT_URL] = getenv('SSO_REDIRECT_URL') ?: 'https://www.marketplace.myworld.com/login_check';

$config[KernelConstants::DOMAIN_WHITELIST] = array_merge($config[KernelConstants::DOMAIN_WHITELIST], [
    'id-test.cashbackworld.com', // SSO Oauth domain
    'id.cashbackworld.com', // SSO Oauth domain
    'preprod-marketplace-gateway.myworldwebservices.com', // MyWorld Services Domain
    'marketplace-gateway.myworldwebservices.com', // MyWorld Services Domain
]);
