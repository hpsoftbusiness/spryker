<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Refund\Business\Model\RefundCalculator;

use ArrayObject;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\RefundTransfer;
use Pyz\Zed\Refund\Business\Calculator\Expense\ExpensePaymentRefundCalculatorInterface;
use Spryker\Zed\Refund\Business\Model\RefundCalculator\ExpenseRefundCalculator as SprykerExpenseRefundCalculator;

class ExpenseRefundCalculator extends SprykerExpenseRefundCalculator
{
    /**
     * @var \Pyz\Zed\Refund\Business\Calculator\Expense\ExpensePaymentRefundCalculatorInterface
     */
    private $expensePaymentRefundCalculator;

    /**
     * @param \Pyz\Zed\Refund\Business\Calculator\Expense\ExpensePaymentRefundCalculatorInterface $expensePaymentRefundCalculator
     */
    public function __construct(ExpensePaymentRefundCalculatorInterface $expensePaymentRefundCalculator)
    {
        $this->expensePaymentRefundCalculator = $expensePaymentRefundCalculator;
    }

    /**
     * @param \Generated\Shared\Transfer\RefundTransfer $refundTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem[] $salesOrderItems
     *
     * @return \Generated\Shared\Transfer\RefundTransfer
     */
    public function calculateRefund(
        RefundTransfer $refundTransfer,
        OrderTransfer $orderTransfer,
        array $salesOrderItems
    ): RefundTransfer {
        $refundTransfer = parent::calculateRefund($refundTransfer, $orderTransfer, $salesOrderItems);

        $this->calculateExpensesRefundByPayment($refundTransfer->getExpenses(), $orderTransfer);

        return $refundTransfer;
    }

    /**
     * @param iterable|\Generated\Shared\Transfer\ExpenseTransfer[] $expenseTransfers
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    private function calculateExpensesRefundByPayment(iterable $expenseTransfers, OrderTransfer $orderTransfer): void
    {
        $paymentTransfers = $orderTransfer->getPayments()->getArrayCopy();
        foreach ($expenseTransfers as $expenseTransfer) {
            $this->expensePaymentRefundCalculator->calculateExpenseRefunds($expenseTransfer, $paymentTransfers);
        }

        $orderTransfer->setPayments(new ArrayObject($paymentTransfers));
    }
}
