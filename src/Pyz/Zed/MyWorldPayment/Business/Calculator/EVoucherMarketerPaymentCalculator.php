<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MyWorldPayment\Business\Calculator;

use Generated\Shared\Transfer\CalculableObjectTransfer;
use Generated\Shared\Transfer\CustomerTransfer;

class EVoucherMarketerPaymentCalculator extends AbstractCashbackPaymentCalculator
{
    /**
     * @return string
     */
    protected function getPaymentName(): string
    {
        return $this->myWorldPaymentConfig->getEVoucherOnBehalfOfMarketerPaymentName();
    }

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return bool
     */
    protected function isPaymentSelected(CalculableObjectTransfer $calculableObjectTransfer): bool
    {
        return (bool)$calculableObjectTransfer->getUseEVoucherOnBehalfOfMarketer();
    }

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     * @param int $usableBalanceAmount
     *
     * @return void
     */
    protected function setTotalUsedBalanceForQuote(
        CalculableObjectTransfer $calculableObjectTransfer,
        int $usableBalanceAmount
    ): void {
        $calculableObjectTransfer->setTotalUsedEVoucherMarketerBalanceAmount($usableBalanceAmount);
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return int
     */
    protected function getCustomerBalanceAmount(CustomerTransfer $customerTransfer): int
    {
        return $this->customerService->getCustomerMarketerEVoucherBalanceAmount($customerTransfer);
    }
}
