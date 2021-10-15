<?php

use Pyz\Shared\Weclapp\WeclappConstants;

// ----------------------------------------------------------------------------
// ------------------ Weclapp integration -------------------------------------
// ----------------------------------------------------------------------------

$config[WeclappConstants::API_URL] = 'https://taebnmrziyledsl.weclapp.com/webapp/api/v1';
$config[WeclappConstants::API_TOKEN] = getenv('WECLAPP_API_TOKEN');
$config[WeclappConstants::CUSTOM_ATTRIBUTE_KEY_MY_WORLD_CUSTOMER_ID] = '7712';
$config[WeclappConstants::CUSTOM_ATTRIBUTE_KEY_CASHBACK_ID] = '7724';
$config[WeclappConstants::CUSTOM_ATTRIBUTE_KEY_CASHBACK_CARD_NUMBER] = '7740';
