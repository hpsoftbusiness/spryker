<?php

use Pyz\Shared\Api\ApiConstants;
use Pyz\Shared\CheckoutPage\CheckoutPageConstants;
use Pyz\Shared\Country\CountryConstants;
use Pyz\Shared\Currency\CurrencyConstants;
use Pyz\Shared\MyWorldMarketplaceApi\MyWorldMarketplaceApiConstants;
use Pyz\Shared\Oms\OmsConstants;
use Pyz\Shared\ProductAffiliate\ProductAffiliateConstants;
use Pyz\Shared\ProductDataImport\ProductDataImportConstants;
use Pyz\Shared\SalesInvoice\SalesInvoiceConstants;

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

$config[OmsConstants::MAIL_ORDER_IN_PROCESSING_RECIPIENT_EMAIL] = 'mp.vendormgmt@myworld.com';
$config[OmsConstants::MAIL_ORDER_IN_PROCESSING_RECIPIENT_NAME] = 'Warehouse Manager';

$config[SalesInvoiceConstants::ORDER_INVOICE_RECIPIENTS_BCC] = [
    'mp.vendormgmt@myworld.com' => 'Warehouse Manager',
];

// ----------------------------------------------------------------------------
// ----------------------- MyWorld Marketplace API ----------------------------
// ----------------------------------------------------------------------------

require 'common/config_my-world-marketplace-api-prod.php';
$config[MyWorldMarketplaceApiConstants::ORDER_REFERENCE_PREFIX] = 'azx0z57777';

$config[ProductAffiliateConstants::TRACKING_URL_PATH] = getenv('AFFILIATE_TRACKING_URL_PATH');

$config[ProductDataImportConstants::STORAGE_NAME] = 'aws-files-import';

// ----------------------------------------------------------------------------
// ------------------------------ ZED API ------------------------------------
// ----------------------------------------------------------------------------

$config[ApiConstants::X_SPRYKER_API_KEY] = '7=hj<K5nnbku}Rdhb5_E?[&k\a"hmmE}';

require 'common/config_my-world-payment.php';

// ----------------------------------------------------------------------------
// ----------------------- MyWorld Payment API ----------------------------
// ----------------------------------------------------------------------------
require 'common/config_my-world-payment-api-prod.php';

// ----------------------------------------------------------------------------
// ----------------------- Checkout feature flags -----------------------------
// ----------------------------------------------------------------------------
$config[CheckoutPageConstants::IS_CASHBACK_PAYMENT_FEATURE_ENABLED] = false;
$config[CheckoutPageConstants::IS_BENEFIT_DEAL_PAYMENT_FEATURE_ENABLED] = false;

// ----------------------------------------------------------------------------
// ------------------ Currency/country feature flags --------------------------
// ----------------------------------------------------------------------------

$config[CountryConstants::IS_MULTI_COUNTRY_FEATURE_ENABLED] = true;
$config[CurrencyConstants::IS_MULTI_CURRENCY_FEATURE_ENABLED] = true;
