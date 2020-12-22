<?php

use Pyz\Shared\MyWorldMarketplaceApi\MyWorldMarketplaceApiConstants;
use Pyz\Shared\Oms\OmsConstants;
use Pyz\Shared\ProductAffiliate\ProductAffiliateConstants;
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

$config[OmsConstants::MAIL_ORDER_IN_PROCESSING_RECIPIENT_EMAIL] = 'sven.stumpf@myworld.com';
$config[OmsConstants::MAIL_ORDER_IN_PROCESSING_RECIPIENT_NAME] = 'Warehouse Manager';

$config[SalesInvoiceConstants::ORDER_INVOICE_RECIPIENTS_BCC] = [
    'sven.stumpf@myworld.com' => 'Warehouse Manager',
];

// ----------------------------------------------------------------------------
// ----------------------- MyWorld Marketplace API ----------------------------
// ----------------------------------------------------------------------------

require 'common/config_my-world-marketplace-api-prod.php';
$config[MyWorldMarketplaceApiConstants::ORDER_REFERENCE_PREFIX] = 'azx0z5d777';

$config[ProductAffiliateConstants::TRACKING_URL_PATH] = getenv('AFFILIATE_TRACKING_URL_PATH');
