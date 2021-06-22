<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Refund\Business\Calculator\Expense;

use Generated\Shared\Transfer\ExpenseTransfer;

interface ExpensePaymentRefundCalculatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\ExpenseTransfer $expenseTransfer
     * @param \Generated\Shared\Transfer\PaymentTransfer[] $paymentTransfers
     *
     * @return \Generated\Shared\Transfer\ExpenseTransfer
     */
    public function calculateExpenseRefunds(ExpenseTransfer $expenseTransfer, array $paymentTransfers): ExpenseTransfer;
}
