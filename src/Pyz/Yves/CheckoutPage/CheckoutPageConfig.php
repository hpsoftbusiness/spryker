<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\CheckoutPage;

use Pyz\Shared\MyWorldPayment\MyWorldPaymentConstants;
use SprykerEco\Shared\Adyen\AdyenConstants;
use SprykerShop\Yves\CheckoutPage\CheckoutPageConfig as SprykerCheckoutPageConfig;

class CheckoutPageConfig extends SprykerCheckoutPageConfig
{
    /**
     * @return string[]
     */
    public function getLocalizedTermsAndConditionsPageLinks(): array
    {
        return [
            'en_US' => '/en/gtc',
            'de_DE' => '/de/agb',
        ];
    }

    /**
     * @return bool
     */
    public function isAdyenCreditCard3dSecureEnabled(): bool
    {
        return $this->get(AdyenConstants::CREDIT_CARD_3D_SECURE_ENABLED);
    }

    /**
     * @return string
     */
    public function getEVoucherPaymentName(): string
    {
        return $this->get(MyWorldPaymentConstants::PAYMENT_NAME_E_VOUCHER);
    }

    /**
     * @return string
     */
    public function getBenefitSalesPriceKey(): string
    {
        return $this->get(MyWorldPaymentConstants::PRODUCT_ATTRIBUTE_KEY_BENEFIT_STORE_SALES_PRICE);
    }

    /**
     * @return string
     */
    public function getBenefitAmountKey(): string
    {
        return $this->get(MyWorldPaymentConstants::PRODUCT_ATTRIBUTE_KEY_BENEFIT_AMOUNT);
    }

    /**
     * @return string
     */
    public function getBenefitStoreKey(): string
    {
        return $this->get(MyWorldPaymentConstants::PRODUCT_ATTRIBUTE_KEY_BENEFIT_STORE);
    }
}
