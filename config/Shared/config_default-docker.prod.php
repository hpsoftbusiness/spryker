<?php

use Pyz\Shared\Api\ApiConstants;
use Pyz\Shared\ApiKeyAuthRestApi\ApiKeyAuthRestApiConstants;
use Pyz\Shared\CheckoutPage\CheckoutPageConstants;
use Pyz\Shared\Country\CountryConstants;
use Pyz\Shared\Currency\CurrencyConstants;
use Pyz\Shared\MyWorldMarketplaceApi\MyWorldMarketplaceApiConstants;
use Pyz\Shared\Oms\OmsConstants;
use Pyz\Shared\ProductAffiliate\ProductAffiliateConstants;
use Pyz\Shared\ProductDataImport\ProductDataImportConstants;
use Pyz\Shared\SalesInvoice\SalesInvoiceConstants;
use Spryker\Shared\Event\EventConstants;

// ############################################################################
// ############################## DEMO/TESTING CONFIGURATION ##################
// ############################################################################

// ----------------------------------------------------------------------------
// ------------------------------ AUTHENTICATION ------------------------------
// ----------------------------------------------------------------------------

require 'common/config_oauth-prod.php';

// ----------------------------------------------------------------------------
// ------------------------------ OMS -----------------------------------------
// ----------------------------------------------------------------------------

require 'common/config_oms-prod.php';

// ----------------------------------------------------------------------------
// ------------------------------ PAYMENTS ------------------------------------
// ----------------------------------------------------------------------------

// >>> ADYEN

require 'common/config_adyen-prod.php';

// ----------------------------------------------------------------------------
// --------------------------- Single Sigh On ---------------------------------
// ----------------------------------------------------------------------------

// >>> SSO

require 'common/config_sso-prod.php';

// ----------------------------------------------------------------------------
// ------------------------------ MAIL ----------------------------------------
// ----------------------------------------------------------------------------

$config[OmsConstants::MAIL_ORDER_IN_PROCESSING_RECIPIENTS] = [
    'mp.vendormgmt@myworld.com' => 'Warehouse Manager',
    'mterm@myworld.com' => 'Warehouse Manager',
];

$config[SalesInvoiceConstants::ORDER_INVOICE_RECIPIENTS_TO] = [
    'mp.vendormgmt@myworld.com' => 'Warehouse Manager',
];

$config[SalesInvoiceConstants::ORDER_INVOICE_RECIPIENTS_BCC] = [];

// ----------------------------------------------------------------------------
// ----------------------- MyWorld Marketplace API ----------------------------
// ----------------------------------------------------------------------------

require 'common/config_my-world-marketplace-api-prod.php';
$config[MyWorldMarketplaceApiConstants::ORDER_REFERENCE_PREFIX] = 'azx0z77777';

$config[ProductAffiliateConstants::TRACKING_URL_PATH] = getenv('AFFILIATE_TRACKING_URL_PATH');

$config[ProductDataImportConstants::STORAGE_NAME] = 'aws-files-import';

// ----------------------------------------------------------------------------
// ------------------------------ ZED API ------------------------------------
// ----------------------------------------------------------------------------
// TODO: @deprecated Please remove this when you remove the Product Feed API from ZED.
$config[ApiConstants::X_SPRYKER_API_KEY] = '7=hj<K5nnbku}Rdhb5_E?[&k\a"hmmE}';

require 'common/config_my-world-payment.php';

// ----------------------------------------------------------------------------
// ----------------------- MyWorld Payment API ----------------------------
// ----------------------------------------------------------------------------
require 'common/config_my-world-payment-api-prod.php';

// ----------------------------------------------------------------------------
// ----------------------- Checkout feature flags -----------------------------
// ----------------------------------------------------------------------------
$config[CheckoutPageConstants::IS_CASHBACK_PAYMENT_FEATURE_ENABLED] = true;
$config[CheckoutPageConstants::IS_BENEFIT_DEAL_PAYMENT_FEATURE_ENABLED] = true;

// ----------------------------------------------------------------------------
// ------------------ Currency/country feature flags --------------------------
// ----------------------------------------------------------------------------

$config[CountryConstants::IS_MULTI_COUNTRY_FEATURE_ENABLED] = false;
$config[CurrencyConstants::IS_MULTI_CURRENCY_FEATURE_ENABLED] = false;

$config[EventConstants::LOGGER_ACTIVE] = true;

// ----------------------------------------------------------------------------
// ------------------------------ API -----------------------------------------
// ----------------------------------------------------------------------------

$config[ApiKeyAuthRestApiConstants::API_KEY] = getenv('GLUE_APPLICATION_API_KEY') ?: '7=hj<K5nnbku}Rdhb5_E?[&k\a"hmmE}';

if (!empty(getenv('SPRYKER_CLUSTER'))) {
    return require('clusters/' . getenv('SPRYKER_CLUSTER') . '/config_default-docker.prod.php');
}
