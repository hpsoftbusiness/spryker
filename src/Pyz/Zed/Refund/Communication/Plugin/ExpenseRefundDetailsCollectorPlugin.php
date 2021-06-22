<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Refund\Communication\Plugin;

use ArrayObject;
use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\RefundDetailCollectionTransfer;
use Pyz\Zed\Refund\RefundConfig;

class ExpenseRefundDetailsCollectorPlugin extends AbstractRefundDetailsCollectorPlugin
{
    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\RefundDetailCollectionTransfer[]
     */
    public function collect(OrderTransfer $orderTransfer): array
    {
        $expenseTransfers = $this->filterExpensesWithRefunds($orderTransfer->getExpenses()->getArrayCopy());
        $mappedPayments = $this->mapPaymentsById($orderTransfer->getPayments());
        $refundDetailCollectionTransfers = [];
        foreach ($expenseTransfers as $expenseTransfer) {
            $refundDetailCollectionTransfers[] = $this->createExpenseRefundDetailsTransfers($expenseTransfer, $mappedPayments);
        }

        return $refundDetailCollectionTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ExpenseTransfer[] $expenseTransfers
     *
     * @return \Generated\Shared\Transfer\ExpenseTransfer[]
     */
    private function filterExpensesWithRefunds(array $expenseTransfers): array
    {
        return array_filter(
            $expenseTransfers,
            function (ExpenseTransfer $expenseTransfer): bool {
                return $expenseTransfer->getRefunds()->count() > 0;
            }
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ExpenseTransfer $expenseTransfer
     * @param \Generated\Shared\Transfer\PaymentTransfer[] $mappedPayments
     *
     * @return \Generated\Shared\Transfer\RefundDetailCollectionTransfer
     */
    private function createExpenseRefundDetailsTransfers(ExpenseTransfer $expenseTransfer, array $mappedPayments): RefundDetailCollectionTransfer
    {
        $refundDetailCollectionTransfer = new RefundDetailCollectionTransfer();
        $refundDetailCollectionTransfer->setName($expenseTransfer->getName());
        $refundDetailCollectionTransfer->setType(RefundConfig::REFUND_DETAIL_TYPE_ITEM);
        $refundDetailCollectionTransfer->setId($expenseTransfer->getIdSalesExpense());
        $refundDetailTransfers = $this->mapRefundsToDetailTransfers($expenseTransfer->getRefunds(), $mappedPayments);
        $refundDetailCollectionTransfer->setRefundDetails(new ArrayObject($refundDetailTransfers));

        return $refundDetailCollectionTransfer;
    }
}
