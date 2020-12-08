<?php

use Pyz\Shared\MyWorldMarketplaceApi\MyWorldMarketplaceApiConstants;
use Pyz\Shared\Oms\OmsConstants;

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

require 'common/config_adyen-development.php';

// ----------------------------------------------------------------------------
// --------------------------- Single Sigh On ---------------------------------
// ----------------------------------------------------------------------------

// >>> SSO

require 'common/config_sso-demo.php';

// ----------------------------------------------------------------------------
// ------------------------------ MAIL ----------------------------------------
// ----------------------------------------------------------------------------

$config[OmsConstants::MAIL_ORDER_IN_PROCESSING_RECIPIENT_EMAIL] = 'nataliia.popkova@spryker.com';
$config[OmsConstants::MAIL_ORDER_IN_PROCESSING_RECIPIENT_NAME] = 'Warehouse Manager';

// ----------------------------------------------------------------------------
// ----------------------- MyWorld Marketplace API ----------------------------
// ----------------------------------------------------------------------------

require 'common/config_my-world-marketplace-api-development.php';
$config[MyWorldMarketplaceApiConstants::ORDER_REFERENCE_PREFIX] = 'azx0z5d186';
