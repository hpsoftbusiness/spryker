<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MyWorldPayment;

use Pyz\Shared\MyWorldPayment\MyWorldPaymentConstants;
use Pyz\Zed\MyWorldPaymentApi\MyWorldPaymentApiConfig;
use Spryker\Zed\Kernel\AbstractBundleConfig;

/**
 * @method \Pyz\Shared\MyWorldPayment\MyWorldPaymentConfig getSharedConfig()
 */
class MyWorldPaymentConfig extends AbstractBundleConfig
{
    public const PAYMENT_METHOD_NAME = 'EVoucher';
    public const PAYMENT_METHOD_BENEFIT_VOUCHER_NAME = 'BenefitVouchers';

    /**
     * Map for payment transaction status codes to glossary translation key.
     */
    public const PAYMENT_TRANSACTION_ERRORS_DESCRIPTION_MAP = [
        MyWorldPaymentApiConfig::PAYMENT_TRANSACTION_STATUS_CODE_FAILED => 'checkout.payment.my_world.error.transaction_failed',
        MyWorldPaymentApiConfig::PAYMENT_TRANSACTION_STATUS_CODE_EXCEEDED_VALUE => 'checkout.payment.my_world.error.transaction_exceeded_value',
        MyWorldPaymentApiConfig::PAYMENT_TRANSACTION_STATUS_CODE_BATCH_FAILED => 'checkout.payment.my_world.error.transaction_batch_failed',
        MyWorldPaymentApiConfig::PAYMENT_TRANSACTION_STATUS_CODE_OPTION_NOT_FOUND => 'checkout.payment.my_world.error.transaction_option_not_found',
    ];

    /**
     * @return int
     */
    public function getOptionEVoucher(): int
    {
        return $this->get(MyWorldPaymentConstants::OPTION_EVOUCHER);
    }

    /**
     * @return string
     */
    public function getOptionEVoucherName(): string
    {
        return $this->get(MyWorldPaymentConstants::PAYMENT_NAME_E_VOUCHER);
    }

    /**
     * @return string
     */
    public function getOptionBenefitVoucherName(): string
    {
        return $this->get(MyWorldPaymentConstants::PAYMENT_NAME_BENEFIT_VOUCHER);
    }

    /**
     * @return string
     */
    public function getProductAttributeKeyBenefitStore(): string
    {
        return $this->get(MyWorldPaymentConstants::PRODUCT_ATTRIBUTE_KEY_BENEFIT_STORE);
    }

    /**
     * @return string
     */
    public function getProductAttributeKeyBenefitAmount(): string
    {
        return $this->get(MyWorldPaymentConstants::PRODUCT_ATTRIBUTE_KEY_BENEFIT_AMOUNT);
    }

    /**
     * @return string
     */
    public function getProductAttributeKeyBenefitSalesPrice(): string
    {
        return $this->get(MyWorldPaymentConstants::PRODUCT_ATTRIBUTE_KEY_BENEFIT_STORE_SALES_PRICE);
    }

    /**
     * @return string
     */
    public function getUnitTypeCurrency(): string
    {
        return $this->get(MyWorldPaymentConstants::UNIT_TYPE_CURRENCY);
    }

    /**
     * @return string
     */
    public function getUnitTypeUnit(): string
    {
        return $this->get(MyWorldPaymentConstants::UNIT_TYPE_UNIT);
    }

    /**
     * @return array
     */
    public function getListAvailableOptions(): array
    {
        return $this->get(MyWorldPaymentConstants::LIST_AVAILABLE_OPTIONS);
    }

    /**
     * @return array
     */
    public function getMapOptionNameToOptionId(): array
    {
        return $this->get(MyWorldPaymentConstants::PAYMENT_NAME_TO_PAYMENT_ID_MAP);
    }

    /**
     * @return array
     */
    public function getUnitTypeToOptionIdMap(): array
    {
        return $this->get(MyWorldPaymentConstants::UNIT_TYPE_TO_OPTION_MAP);
    }

    /**
     * @return array
     */
    public function getOptionNameToIdMap(): array
    {
        return $this->get(MyWorldPaymentConstants::OPTION_NAME_TO_ID_MAP);
    }

    /**
     * @return int
     */
    public function getDefaultFlowsType(): int
    {
        return $this->get(MyWorldPaymentConstants::FLOWS_DEFAULT_TYPE);
    }

    /**
     * @return string
     */
    public function getShoppingPointsPaymentName(): string
    {
        return $this->get(MyWorldPaymentConstants::PAYMENT_NAME_SHOPPING_POINTS);
    }

    /**
     * @return int
     */
    public function getShoppingPointsPaymentOptionId(): int
    {
        return $this->get(MyWorldPaymentConstants::OPTION_SHOPPING_POINTS_ACCOUNT);
    }

    /**
     * @return string
     */
    public function getMyWorldPaymentProviderKey(): string
    {
        return $this->getSharedConfig()::PAYMENT_PROVIDER_NAME_MY_WORLD;
    }
}
