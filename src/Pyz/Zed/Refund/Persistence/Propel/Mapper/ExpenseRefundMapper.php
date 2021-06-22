<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Refund\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\ExpenseRefundTransfer;
use Orm\Zed\Refund\Persistence\PyzSalesExpenseRefund;

class ExpenseRefundMapper
{
    /**
     * @param \Orm\Zed\Refund\Persistence\PyzSalesExpenseRefund[] $pyzSalesExpenseRefundEntities
     *
     * @return \Generated\Shared\Transfer\ExpenseRefundTransfer[]
     */
    public function mapExpenseRefundEntityCollectionToTransfers(array $pyzSalesExpenseRefundEntities): array
    {
        $expenseRefundTransfers = [];
        foreach ($pyzSalesExpenseRefundEntities as $pyzSalesExpenseRefund) {
            $expenseRefundTransfers[] = $this->mapExpenseRefundEntityToTransfer(
                $pyzSalesExpenseRefund,
                new ExpenseRefundTransfer()
            );
        }

        return $expenseRefundTransfers;
    }

    /**
     * @param \Orm\Zed\Refund\Persistence\PyzSalesExpenseRefund $pyzSalesExpenseRefund
     * @param \Generated\Shared\Transfer\ExpenseRefundTransfer $expenseRefundTransfer
     *
     * @return \Generated\Shared\Transfer\ExpenseRefundTransfer
     */
    public function mapExpenseRefundEntityToTransfer(
        PyzSalesExpenseRefund $pyzSalesExpenseRefund,
        ExpenseRefundTransfer $expenseRefundTransfer
    ): ExpenseRefundTransfer {
        return $expenseRefundTransfer->fromArray($pyzSalesExpenseRefund->toArray(), true);
    }

    /**
     * @param \Generated\Shared\Transfer\ExpenseRefundTransfer $expenseRefundTransfer
     * @param \Orm\Zed\Refund\Persistence\PyzSalesExpenseRefund $pyzSalesExpenseRefund
     *
     * @return \Orm\Zed\Refund\Persistence\PyzSalesExpenseRefund
     */
    public function mapExpenseRefundTransferToEntity(
        ExpenseRefundTransfer $expenseRefundTransfer,
        PyzSalesExpenseRefund $pyzSalesExpenseRefund
    ): PyzSalesExpenseRefund {
        $pyzSalesExpenseRefund->fromArray($expenseRefundTransfer->toArray());

        return $pyzSalesExpenseRefund;
    }
}
