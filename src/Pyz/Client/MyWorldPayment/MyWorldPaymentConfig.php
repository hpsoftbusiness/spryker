<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\MyWorldPayment;

use Pyz\Shared\MyWorldPayment\MyWorldPaymentConstants;
use Spryker\Client\Kernel\AbstractBundleConfig;

class MyWorldPaymentConfig extends AbstractBundleConfig
{
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
     * @return string
     */
    public function getPaymentNameEVoucher(): string
    {
        return $this->get(MyWorldPaymentConstants::PAYMENT_NAME_E_VOUCHER);
    }

    /**
     * @return string
     */
    public function getPaymentNameCashback(): string
    {
        return $this->get(MyWorldPaymentConstants::PAYMENT_NAME_CASHBACK);
    }

    /**
     * @return string
     */
    public function getPaymentNameEVoucherOnBehalfOfMarketer(): string
    {
        return $this->get(MyWorldPaymentConstants::PAYMENT_NAME_E_VOUCHER_ON_BEHALF_OF_MARKETER);
    }
}
