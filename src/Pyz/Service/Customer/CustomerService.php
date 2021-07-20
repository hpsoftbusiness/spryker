<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Service\Customer;

use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Service\Customer\CustomerService as SprykerCustomerService;
use SprykerShop\Yves\CheckoutPage\Dependency\Service\CheckoutPageToCustomerServiceInterface;

/**
 * @method \Pyz\Service\Customer\CustomerServiceFactory getFactory()
 */
class CustomerService extends SprykerCustomerService implements
    CustomerServiceInterface,
    CheckoutPageToCustomerServiceInterface
{
    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return float
     */
    public function getCustomerShoppingPointsBalanceAmount(CustomerTransfer $customerTransfer): float
    {
        return $this->getFactory()->createBalanceResolver()->resolveBalanceAmountWithoutConversion(
            $customerTransfer,
            $this->getFactory()->getConfig()->getPaymentOptionIdShoppingPoints()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return int
     */
    public function getCustomerEVoucherBalanceAmount(CustomerTransfer $customerTransfer): int
    {
        return $this->getFactory()->createBalanceResolver()->resolveBalanceAmount(
            $customerTransfer,
            $this->getFactory()->getConfig()->getPaymentOptionIdEVoucher()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return int
     */
    public function getCustomerCashbackBalanceAmount(CustomerTransfer $customerTransfer): int
    {
        return $this->getFactory()->createBalanceResolver()->resolveBalanceAmount(
            $customerTransfer,
            $this->getFactory()->getConfig()->getPaymentOptionIdCashback()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return int
     */
    public function getCustomerMarketerEVoucherBalanceAmount(CustomerTransfer $customerTransfer): int
    {
        return $this->getFactory()->createBalanceResolver()->resolveBalanceAmount(
            $customerTransfer,
            $this->getFactory()->getConfig()->getPaymentOptionIdEVoucherOnBehalfOfMarketer()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return int
     */
    public function getCustomerBenefitVoucherBalanceAmount(CustomerTransfer $customerTransfer): int
    {
        return $this->getFactory()->createBalanceResolver()->resolveBalanceAmount(
            $customerTransfer,
            $this->getFactory()->getConfig()->getPaymentOptionIdBenefitVoucher()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     * @param int $idPaymentOption
     *
     * @return int
     */
    public function getCustomerBalanceAmountByPaymentOptionId(CustomerTransfer $customerTransfer, int $idPaymentOption): int
    {
        return $this->getFactory()->createBalanceResolver()->resolveBalanceAmount($customerTransfer, $idPaymentOption);
    }
}
