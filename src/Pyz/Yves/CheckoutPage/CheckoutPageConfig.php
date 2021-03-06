<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\CheckoutPage;

use Pyz\Shared\CheckoutPage\CheckoutPageConstants;
use Pyz\Shared\MyWorldPayment\MyWorldPaymentConstants;
use Pyz\Shared\Sso\SsoConstants;
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
    public function getBenefitVoucherPaymentName(): string
    {
        return $this->get(MyWorldPaymentConstants::PAYMENT_NAME_BENEFIT_VOUCHER);
    }

    /**
     * @return bool
     */
    public function isCashbackFeatureEnabled(): bool
    {
        return $this->get(CheckoutPageConstants::IS_CASHBACK_PAYMENT_FEATURE_ENABLED);
    }

    /**
     * @return bool
     */
    public function isBenefitDealFeatureEnabled(): bool
    {
        return $this->get(CheckoutPageConstants::IS_BENEFIT_DEAL_PAYMENT_FEATURE_ENABLED);
    }

    /**
     * @return int
     */
    public function getPaymentOptionIdEVoucher(): int
    {
        return $this->get(MyWorldPaymentConstants::OPTION_EVOUCHER);
    }

    /**
     * @return int
     */
    public function getPaymentOptionIdCashback(): int
    {
        return $this->get(MyWorldPaymentConstants::OPTION_CASHBACK_ACCOUNT);
    }

    /**
     * @return int
     */
    public function getPaymentOptionIdEVoucherOnBehalfOfMarketer(): int
    {
        return $this->get(MyWorldPaymentConstants::OPTION_EVOUCHER_ON_BEHALF_OF_MARKETER);
    }

    /**
     * @return bool
     */
    public function isSsoLoginEnabled(): bool
    {
        return $this->get(SsoConstants::SSO_LOGIN_ENABLED, false);
    }
}
