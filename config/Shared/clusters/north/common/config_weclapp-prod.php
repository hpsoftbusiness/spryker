<?php

use Pyz\Shared\Weclapp\WeclappConstants;

// ----------------------------------------------------------------------------
// ------------------ Weclapp integration -------------------------------------
// ----------------------------------------------------------------------------
$weclappSpecificConfig = [
    'NO' => [
        WeclappConstants::API_URL => 'https://ulmpoexvpsrtwdl.weclapp.com/webapp/api/v1',
        WeclappConstants::API_TOKEN => getenv('WECLAPP_API_TOKEN_NO'),
        WeclappConstants::CUSTOM_ATTRIBUTE_KEY_MY_WORLD_CUSTOMER_ID => '3882',
        WeclappConstants::CUSTOM_ATTRIBUTE_KEY_CASHBACK_ID => '3894',
        WeclappConstants::CUSTOM_ATTRIBUTE_KEY_CASHBACK_CARD_NUMBER => '3910',
    ],
];

if (isset($weclappSpecificConfig[APPLICATION_STORE]) && is_array($weclappSpecificConfig[APPLICATION_STORE])) {
    $config[WeclappConstants::API_URL] = $weclappSpecificConfig[APPLICATION_STORE][WeclappConstants::API_URL];
    $config[WeclappConstants::API_TOKEN] = $weclappSpecificConfig[APPLICATION_STORE][WeclappConstants::API_TOKEN];
    $config[WeclappConstants::CUSTOM_ATTRIBUTE_KEY_MY_WORLD_CUSTOMER_ID] = $weclappSpecificConfig[APPLICATION_STORE][WeclappConstants::CUSTOM_ATTRIBUTE_KEY_MY_WORLD_CUSTOMER_ID];
    $config[WeclappConstants::CUSTOM_ATTRIBUTE_KEY_CASHBACK_ID] = $weclappSpecificConfig[APPLICATION_STORE][WeclappConstants::CUSTOM_ATTRIBUTE_KEY_CASHBACK_ID];
    $config[WeclappConstants::CUSTOM_ATTRIBUTE_KEY_CASHBACK_CARD_NUMBER] = $weclappSpecificConfig[APPLICATION_STORE][WeclappConstants::CUSTOM_ATTRIBUTE_KEY_CASHBACK_CARD_NUMBER];
}
