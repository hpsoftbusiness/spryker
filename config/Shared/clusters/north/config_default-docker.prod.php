<?php

use Pyz\Shared\Adyen\AdyenConstants;
use Pyz\Shared\Country\CountryConstants;
use Pyz\Shared\Locale\LocaleConstants;
use Pyz\Shared\MyWorldMarketplaceApi\MyWorldMarketplaceApiConstants;
use Pyz\Shared\SalesInvoice\SalesInvoiceConstants;
use SprykerEco\Shared\AdyenApi\AdyenApiConstants;

$config[CountryConstants::CLUSTER_COUNTRIES] = ['NO', 'FI', 'DK', 'SE'];
$config[LocaleConstants::COUNTRY_TO_LOCALE_RELATIONS] = [
    'NO' => 'nb_NO',
    'FI' => 'fi_FI',
    'DK' => 'da_DK',
    'SE' => 'sv_SE',
];

$adyenCredentials = [
    'ORIGIN_KEYS' => [
        'NO' => getenv('ADYEN_ORIGIN_KEYS_NO') ?: '',
        'FI' => getenv('ADYEN_ORIGIN_KEYS_FI') ?: '',
        'DK' => getenv('ADYEN_ORIGIN_KEYS_DK') ?: '',
        'SE' => getenv('ADYEN_ORIGIN_KEYS_SE') ?: '',
    ],
    'API_KEYS' => [
        'NO' => getenv('ADYEN_API_KEYS_NO') ?: '',
        'FI' => getenv('ADYEN_API_KEYS_FI') ?: '',
        'DK' => getenv('ADYEN_API_KEYS_DK') ?: '',
        'SE' => getenv('ADYEN_API_KEYS_SE') ?: '',
    ],
    'MERCHANT_ACCOUNTS' => [
        'NO' => 'MyWorldNordicAS',
        'FI' => '',
        'DK' => '',
        'SE' => '',
    ],
];
$config[AdyenApiConstants::API_KEY] = $adyenCredentials['API_KEYS'][APPLICATION_STORE];
$config[AdyenConstants::MERCHANT_ACCOUNT] = $adyenCredentials['MERCHANT_ACCOUNTS'][APPLICATION_STORE];
$config[AdyenConstants::SDK_CHECKOUT_ORIGIN_KEY] = $adyenCredentials['ORIGIN_KEYS'][APPLICATION_STORE];

$adyenSplitAccounts = [
    'NO' => '147890109',
    'FI' => '',
    'DK' => '',
    'SE' => '',
];

$config[AdyenConstants::SPLIT_ACCOUNT] = $adyenSplitAccounts[APPLICATION_STORE];
$config[AdyenConstants::SPLIT_ACCOUNT_COMMISSION_INTEREST] = 0;

$config[MyWorldMarketplaceApiConstants::DEALER_ID_DEFAULT] = '31d25f30-e540-4825-b602-adc7009eded8';

// ----------------------------------------------------------------------------
// ------------------ Weclapp integration -------------------------------------
// ----------------------------------------------------------------------------

require 'common/config_weclapp-prod.php';

// ----------------------------------------------------------------------------
// ------------------------------ MAIL ----------------------------------------
// ----------------------------------------------------------------------------

$config[SalesInvoiceConstants::ORDER_INVOICE_RECIPIENTS_TO] = [
    'vendormgmt.no@myworld.com' => 'Warehouse Manager Norway',
];
