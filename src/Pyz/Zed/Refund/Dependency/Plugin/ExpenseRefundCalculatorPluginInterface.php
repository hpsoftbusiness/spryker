<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Refund\Dependency\Plugin;

use Generated\Shared\Transfer\ExpenseRefundTransfer;
use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\PaymentTransfer;

interface ExpenseRefundCalculatorPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\ExpenseTransfer $expenseTransfer
     * @param \Generated\Shared\Transfer\PaymentTransfer $paymentTransfer
     *
     * @return \Generated\Shared\Transfer\ExpenseRefundTransfer|null
     */
    public function calculateExpenseRefund(ExpenseTransfer $expenseTransfer, PaymentTransfer $paymentTransfer): ?ExpenseRefundTransfer;

    /**
     * @param \Generated\Shared\Transfer\PaymentTransfer[] $paymentTransfers
     *
     * @return \Generated\Shared\Transfer\PaymentTransfer|null
     */
    public function findApplicablePayment(array $paymentTransfers): ?PaymentTransfer;
}
