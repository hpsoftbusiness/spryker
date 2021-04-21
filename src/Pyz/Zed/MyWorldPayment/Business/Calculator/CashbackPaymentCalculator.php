<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MyWorldPayment\Business\Calculator;

use Generated\Shared\Transfer\CalculableObjectTransfer;

class CashbackPaymentCalculator extends AbstractCashbackPaymentCalculator
{
    /**
     * @return int
     */
    protected function getPaymentOptionId(): int
    {
        return $this->myWorldPaymentConfig->getOptionCashback();
    }

    /**
     * @return string
     */
    protected function getPaymentName(): string
    {
        return $this->myWorldPaymentConfig->getCashbackPaymentName();
    }

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return bool
     */
    protected function isPaymentSelected(CalculableObjectTransfer $calculableObjectTransfer): bool
    {
        return (bool)$calculableObjectTransfer->getUseCashbackBalance();
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
        $calculableObjectTransfer->setTotalUsedCashbackBalanceAmount($usableBalanceAmount);
    }
}
