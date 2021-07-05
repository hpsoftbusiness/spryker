<?php

use Pyz\Shared\Adyen\AdyenConfig;
use Pyz\Shared\DummyPrepayment\DummyPrepaymentConfig;
use Pyz\Zed\MyWorldPayment\MyWorldPaymentConfig;
use Spryker\Shared\Kernel\KernelConstants;
use Spryker\Shared\Nopayment\NopaymentConfig;
use Spryker\Shared\Nopayment\NopaymentConstants;
use Spryker\Shared\Oms\OmsConstants;
use Spryker\Shared\Sales\SalesConstants;
use Spryker\Zed\GiftCard\GiftCardConfig;

// ----------------------------------------------------------------------------
// ------------------------------ OMS -----------------------------------------
// ----------------------------------------------------------------------------

$config[KernelConstants::DEPENDENCY_INJECTOR_YVES] = [
    'CheckoutPage' => [
        NopaymentConfig::PAYMENT_PROVIDER_NAME,
    ],
];
$config[KernelConstants::DEPENDENCY_INJECTOR_ZED] = [
    'Payment' => [
        GiftCardConfig::PROVIDER_NAME,
        NopaymentConfig::PAYMENT_PROVIDER_NAME,
        MyWorldPaymentConfig::PAYMENT_METHOD_BENEFIT_VOUCHER_NAME,
        MyWorldPaymentConfig::PAYMENT_METHOD_EVOUCHER_NAME,
        MyWorldPaymentConfig::PAYMENT_METHOD_CASHBACK_NAME,
        MyWorldPaymentConfig::PAYMENT_METHOD_EVOUCHER_ON_BEHALF_OF_MARKETER_NAME,
    ],
    'Oms' => [
        GiftCardConfig::PROVIDER_NAME,
    ],
];

$config[NopaymentConstants::NO_PAYMENT_METHODS] = [
    NopaymentConfig::PAYMENT_PROVIDER_NAME,
    MyWorldPaymentConfig::PAYMENT_METHOD_BENEFIT_VOUCHER_NAME,
    MyWorldPaymentConfig::PAYMENT_METHOD_EVOUCHER_NAME,
    MyWorldPaymentConfig::PAYMENT_METHOD_CASHBACK_NAME,
    MyWorldPaymentConfig::PAYMENT_METHOD_EVOUCHER_ON_BEHALF_OF_MARKETER_NAME,
];
$config[NopaymentConstants::WHITELIST_PAYMENT_METHODS] = [
    GiftCardConfig::PROVIDER_NAME,
    NopaymentConfig::PAYMENT_PROVIDER_NAME,
    MyWorldPaymentConfig::PAYMENT_METHOD_BENEFIT_VOUCHER_NAME,
    MyWorldPaymentConfig::PAYMENT_METHOD_EVOUCHER_NAME,
    MyWorldPaymentConfig::PAYMENT_METHOD_CASHBACK_NAME,
    MyWorldPaymentConfig::PAYMENT_METHOD_EVOUCHER_ON_BEHALF_OF_MARKETER_NAME,
    AdyenConfig::ADYEN_CREDIT_CARD,
];

$config[OmsConstants::ACTIVE_PROCESSES] = array_merge([
    'Nopayment01',
    'AdyenCreditCard01',
    'DummyPrepayment01',
], $config[OmsConstants::ACTIVE_PROCESSES]);

$config[SalesConstants::PAYMENT_METHOD_STATEMACHINE_MAPPING] = [
    DummyPrepaymentConfig::DUMMY_PREPAYMENT => 'DummyPrepayment01',
    AdyenConfig::ADYEN_CREDIT_CARD => 'AdyenCreditCard01',
];
