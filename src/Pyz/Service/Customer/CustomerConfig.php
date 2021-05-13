<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Service\Customer;

use Pyz\Shared\MyWorldPayment\MyWorldPaymentConstants;
use Spryker\Service\Customer\CustomerConfig as SprykerCustomerConfig;

class CustomerConfig extends SprykerCustomerConfig
{
    /**
     * @return int
     */
    public function getPaymentOptionIdShoppingPoints(): int
    {
        return $this->get(MyWorldPaymentConstants::OPTION_SHOPPING_POINTS_ACCOUNT);
    }

    /**
     * @return int
     */
    public function getPaymentOptionIdBenefitVoucher(): int
    {
        return $this->get(MyWorldPaymentConstants::OPTION_BENEFIT_VOUCHER_ACCOUNT);
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
}
