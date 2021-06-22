<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Shared\Refund\Calculator;

use Generated\Shared\Transfer\ExpenseRefundTransfer;
use Pyz\Zed\Refund\RefundConfig;

trait ExpenseRefundCalculatorTrait
{
    /**
     * @param int $idSalesExpense
     * @param int $idSalesPayment
     * @param int $refundAmount
     *
     * @return \Generated\Shared\Transfer\ExpenseRefundTransfer
     */
    private function createExpenseRefundTransfer(
        int $idSalesExpense,
        int $idSalesPayment,
        int $refundAmount
    ): ExpenseRefundTransfer {
        $expenseRefundTransfer = new ExpenseRefundTransfer();
        $expenseRefundTransfer->setFkSalesExpense($idSalesExpense);
        $expenseRefundTransfer->setFkSalesPayment($idSalesPayment);
        $expenseRefundTransfer->setAmount($refundAmount);
        $expenseRefundTransfer->setStatus(RefundConfig::PAYMENT_REFUND_STATUS_NEW);

        return $expenseRefundTransfer;
    }
}
