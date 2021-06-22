<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Adyen;

use Pyz\Shared\Adyen\AdyenConstants;
use Pyz\Zed\Refund\RefundConfig;
use SprykerEco\Zed\Adyen\AdyenConfig as SprykerEcoAdyenConfig;

class AdyenConfig extends SprykerEcoAdyenConfig
{
    public const ADYEN_RESULT_CODE_AUTHENTICATION_FINISHED = 'AuthenticationFinished';
    public const ADYEN_RESULT_CODE_AUTHENTICATION_NOT_REQUIRED = 'AuthenticationNotRequired';
    public const ADYEN_RESULT_CODE_AUTHORISED = 'Authorised';
    public const ADYEN_RESULT_CODE_CANCELLED = 'Cancelled';
    public const ADYEN_RESULT_CODE_CHALLENGE_SHOPPER = 'ChallengeShopper';
    public const ADYEN_RESULT_CODE_ERROR = 'Error';
    public const ADYEN_RESULT_CODE_IDENTIFY_SHOPPER = 'IdentifyShopper';
    public const ADYEN_RESULT_CODE_PENDING = 'Pending';
    public const ADYEN_RESULT_CODE_PRESENT_TO_SHOPPER = 'PresentToShopper';
    public const ADYEN_RESULT_CODE_RECEIVED = 'Received';
    public const ADYEN_RESULT_CODE_REDIRECT_SHOPPER = 'RedirectShopper';
    public const ADYEN_RESULT_CODE_REFUSED = 'Refused';

    /**
     * Specification:
     * - Adyen order item payment status to item refund status map.
     */
    public const PAYMENT_TO_REFUND_STATUS_MAP = [
        self::OMS_STATUS_REFUND_PENDING => RefundConfig::PAYMENT_REFUND_STATUS_PENDING,
        self::OMS_STATUS_REFUND_FAILED => RefundConfig::PAYMENT_REFUND_STATUS_FAILED,
        self::OMS_STATUS_REFUNDED => RefundConfig::PAYMENT_REFUND_STATUS_PROCESSED,
    ];

    /**
     * @return string
     */
    public function getSplitAccount(): string
    {
        return $this->get(AdyenConstants::SPLIT_ACCOUNT);
    }

    /**
     * @return float
     */
    public function getSplitAccountCommissionInterest(): float
    {
        return $this->get(AdyenConstants::SPLIT_ACCOUNT_COMMISSION_INTEREST, 0.0);
    }

    /**
     * @return string
     */
    public function getAdyenResultCodeAuthorised(): string
    {
        return static::ADYEN_RESULT_CODE_AUTHORISED;
    }

    /**
     * @return string
     */
    public function getAdyenResultCodeRefused(): string
    {
        return static::ADYEN_RESULT_CODE_REFUSED;
    }

    /**
     * @return string
     */
    public function getAdyenResultCodeError(): string
    {
        return static::ADYEN_RESULT_CODE_ERROR;
    }
}
