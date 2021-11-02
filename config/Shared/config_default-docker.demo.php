<?php

use Pyz\Shared\DataImport\DataImportConstants;
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

require 'common/config_oauth-development.php';

// ----------------------------------------------------------------------------
// ------------------------------ OMS -----------------------------------------
// ----------------------------------------------------------------------------

require 'common/config_oms-development.php';

// ----------------------------------------------------------------------------
// ------------------------------ PAYMENTS ------------------------------------
// ----------------------------------------------------------------------------

// >>> ADYEN

require 'common/config_adyen-demo.php';

// ----------------------------------------------------------------------------
// --------------------------- Single Sigh On ---------------------------------
// ----------------------------------------------------------------------------

// >>> SSO

require 'common/config_sso-demo.php';

// ----------------------------------------------------------------------------
// ------------------------------ MAIL ----------------------------------------
// ----------------------------------------------------------------------------

$config[OmsConstants::MAIL_ORDER_IN_PROCESSING_RECIPIENTS] = [
    'nataliia.popkova@spryker.com' => 'Warehouse Manager',
];

$config[SalesInvoiceConstants::ORDER_INVOICE_RECIPIENTS_TO] = [
    'warehouse.manager@localhost' => 'Warehouse Manager',
];

$config[SalesInvoiceConstants::ORDER_INVOICE_RECIPIENTS_BCC] = [];

// ----------------------------------------------------------------------------
// ----------------------- MyWorld Marketplace API ----------------------------
// ----------------------------------------------------------------------------

require 'common/config_my-world-marketplace-api-development.php';
$config[MyWorldMarketplaceApiConstants::ORDER_REFERENCE_PREFIX] = 'azx0z5d186';

$config[ProductAffiliateConstants::TRACKING_URL_PATH] = 'https://test-click.myworld.com/spryker';

$config[ProductDataImportConstants::STORAGE_NAME] = 'aws-files-import';

// ----------------------------------------------------------------------------
// ----------------------- MyWorld Payment API ----------------------------
// ----------------------------------------------------------------------------
require 'common/config_my-world-payment-api-development.php';

require 'common/config_my-world-payment.php';

$config[EventConstants::LOGGER_ACTIVE] = true;

// ----------------------------------------------------------------------------
// ------------------ Weclapp integration -------------------------------------
// ----------------------------------------------------------------------------

require 'common/config_weclapp-demo.php';

$config[DataImportConstants::NEED_STORE_RELATION_VALIDATION] = (bool)getenv('NEED_STORE_RELATION_VALIDATION') ?? true;

if (!empty(getenv('SPRYKER_CLUSTER'))) {
    return require('clusters/' . getenv('SPRYKER_CLUSTER') . '/config_default-docker.demo.php');
}
