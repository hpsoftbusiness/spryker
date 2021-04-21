<?php

use Pyz\Shared\MyWorldPayment\MyWorldPaymentConstants;

$config[MyWorldPaymentConstants::FLOWS_DEFAULT_TYPE] = 4;
// Unit Types
$config[MyWorldPaymentConstants::UNIT_TYPE_CURRENCY] = 'Currency';
$config[MyWorldPaymentConstants::UNIT_TYPE_UNIT] = 'Unit';
//Payment options names
$config[MyWorldPaymentConstants::PAYMENT_NAME_E_VOUCHER] = 'EVoucher';
$config[MyWorldPaymentConstants::PAYMENT_NAME_E_VOUCHER_ON_BEHALF_OF_MARKETER] = 'EVoucherMarketer';
$config[MyWorldPaymentConstants::PAYMENT_NAME_CASHBACK] = 'Cashback';
$config[MyWorldPaymentConstants::PAYMENT_NAME_BENEFIT_VOUCHER_ACCOUNT] = 'Benefit Voucher Account';
$config[MyWorldPaymentConstants::PAYMENT_NAME_BENEFIT_VOUCHER] = 'BenefitVouchers';
$config[MyWorldPaymentConstants::PAYMENT_NAME_SHOPPING_POINTS] = 'ShoppingPoints';
// Payment options
$config[MyWorldPaymentConstants::OPTION_EVOUCHER] = 1;
$config[MyWorldPaymentConstants::OPTION_EVOUCHER_ON_BEHALF_OF_MARKETER] = 2;
$config[MyWorldPaymentConstants::OPTION_CASHBACK_ACCOUNT] = 6;
$config[MyWorldPaymentConstants::OPTION_MVOUCHER_ACCOUNT] = 8;
$config[MyWorldPaymentConstants::OPTION_SHOPPING_POINTS_ACCOUNT] = 9;
$config[MyWorldPaymentConstants::OPTION_BENEFIT_VOUCHER_ACCOUNT] = 10;
// Payment options names to options ids map
$config[MyWorldPaymentConstants::OPTION_NAME_TO_ID_MAP] = [
    $config[MyWorldPaymentConstants::PAYMENT_NAME_E_VOUCHER] => $config[MyWorldPaymentConstants::OPTION_EVOUCHER],
    $config[MyWorldPaymentConstants::PAYMENT_NAME_E_VOUCHER_ON_BEHALF_OF_MARKETER] = $config[MyWorldPaymentConstants::OPTION_EVOUCHER_ON_BEHALF_OF_MARKETER],
    $config[MyWorldPaymentConstants::PAYMENT_NAME_BENEFIT_VOUCHER_ACCOUNT] => $config[MyWorldPaymentConstants::OPTION_BENEFIT_VOUCHER_ACCOUNT],
    $config[MyWorldPaymentConstants::PAYMENT_NAME_CASHBACK] => $config[MyWorldPaymentConstants::OPTION_CASHBACK_ACCOUNT],
    $config[MyWorldPaymentConstants::PAYMENT_NAME_BENEFIT_VOUCHER] => $config[MyWorldPaymentConstants::OPTION_BENEFIT_VOUCHER_ACCOUNT],
    $config[MyWorldPaymentConstants::PAYMENT_NAME_SHOPPING_POINTS] => $config[MyWorldPaymentConstants::OPTION_SHOPPING_POINTS_ACCOUNT],
];
// Currency/Unit to option map
$config[MyWorldPaymentConstants::UNIT_TYPE_TO_OPTION_MAP] = [
    $config[MyWorldPaymentConstants::OPTION_EVOUCHER] => $config[MyWorldPaymentConstants::UNIT_TYPE_CURRENCY],
    $config[MyWorldPaymentConstants::OPTION_EVOUCHER_ON_BEHALF_OF_MARKETER] => $config[MyWorldPaymentConstants::UNIT_TYPE_CURRENCY],
    $config[MyWorldPaymentConstants::OPTION_CASHBACK_ACCOUNT] => $config[MyWorldPaymentConstants::UNIT_TYPE_CURRENCY],
    $config[MyWorldPaymentConstants::OPTION_MVOUCHER_ACCOUNT] => $config[MyWorldPaymentConstants::UNIT_TYPE_CURRENCY],
    $config[MyWorldPaymentConstants::OPTION_SHOPPING_POINTS_ACCOUNT] => $config[MyWorldPaymentConstants::UNIT_TYPE_UNIT],
    $config[MyWorldPaymentConstants::OPTION_BENEFIT_VOUCHER_ACCOUNT] => $config[MyWorldPaymentConstants::UNIT_TYPE_CURRENCY],

];

$config[MyWorldPaymentConstants::PAYMENT_NAME_TO_PAYMENT_ID_MAP] = [
    $config[MyWorldPaymentConstants::PAYMENT_NAME_E_VOUCHER] => $config[MyWorldPaymentConstants::OPTION_EVOUCHER],
    $config[MyWorldPaymentConstants::PAYMENT_NAME_BENEFIT_VOUCHER_ACCOUNT] => $config[MyWorldPaymentConstants::OPTION_BENEFIT_VOUCHER_ACCOUNT],
    $config[MyWorldPaymentConstants::PAYMENT_NAME_BENEFIT_VOUCHER] => $config[MyWorldPaymentConstants::OPTION_BENEFIT_VOUCHER_ACCOUNT],
    $config[MyWorldPaymentConstants::PAYMENT_NAME_SHOPPING_POINTS] => $config[MyWorldPaymentConstants::OPTION_SHOPPING_POINTS_ACCOUNT],
];

$config[MyWorldPaymentConstants::LIST_AVAILABLE_OPTIONS] = [
   $config[MyWorldPaymentConstants::OPTION_EVOUCHER],
   $config[MyWorldPaymentConstants::OPTION_EVOUCHER_ON_BEHALF_OF_MARKETER],
   $config[MyWorldPaymentConstants::OPTION_CASHBACK_ACCOUNT],
   $config[MyWorldPaymentConstants::OPTION_MVOUCHER_ACCOUNT],
   $config[MyWorldPaymentConstants::OPTION_SHOPPING_POINTS_ACCOUNT],
   $config[MyWorldPaymentConstants::OPTION_BENEFIT_VOUCHER_ACCOUNT],
];

$config[MyWorldPaymentConstants::PRODUCT_ATTRIBUTE_KEY_BENEFIT_STORE] = 'benefit_store';
$config[MyWorldPaymentConstants::PRODUCT_ATTRIBUTE_KEY_BENEFIT_AMOUNT] = 'benefit_amount';
$config[MyWorldPaymentConstants::PRODUCT_ATTRIBUTE_KEY_BENEFIT_STORE_SALES_PRICE] = 'benefit_store_sales_price';
$config[MyWorldPaymentConstants::PRODUCT_ATTRIBUTE_KEY_SHOPPING_POINTS_STORE] = 'shopping_point_store';
$config[MyWorldPaymentConstants::PRODUCT_ATTRIBUTE_KEY_SHOPPING_POINTS_AMOUNT] = 'product_sp_amount';
