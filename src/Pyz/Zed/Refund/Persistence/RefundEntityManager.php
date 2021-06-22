<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Refund\Persistence;

use Generated\Shared\Transfer\ExpenseRefundTransfer;
use Generated\Shared\Transfer\ItemRefundTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Pyz\Zed\Refund\Persistence\RefundPersistenceFactory getFactory()
 */
class RefundEntityManager extends AbstractEntityManager implements RefundEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\ExpenseRefundTransfer $expenseRefundTransfer
     *
     * @return void
     */
    public function saveSalesExpenseRefund(ExpenseRefundTransfer $expenseRefundTransfer): void
    {
        $pyzSalesExpenseRefund = $this->getFactory()->createPyzSalesExpenseRefundQuery()
            ->filterByFkSalesPayment($expenseRefundTransfer->getFkSalesPayment())
            ->filterByFkSalesExpense($expenseRefundTransfer->getFkSalesExpense())
            ->findOneOrCreate();

        $pyzSalesExpenseRefund = $this->getFactory()->createExpenseRefundMapper()
            ->mapExpenseRefundTransferToEntity($expenseRefundTransfer, $pyzSalesExpenseRefund);
        $pyzSalesExpenseRefund->save();
    }

    /**
     * @param \Generated\Shared\Transfer\ItemRefundTransfer $itemRefundTransfer
     *
     * @return void
     */
    public function saveSalesOrderItemRefund(ItemRefundTransfer $itemRefundTransfer): void
    {
        $pyzSalesOrderItemRefund = $this->getFactory()->createPyzSalesOrderItemRefundQuery()
            ->filterByFkSalesOrderItem($itemRefundTransfer->getFkSalesOrderItem())
            ->filterByFkSalesPayment($itemRefundTransfer->getFkSalesPayment())
            ->findOneOrCreate();

        $pyzSalesOrderItemRefund = $this->getFactory()->createItemRefundMapper()
            ->mapItemRefundTransferToEntity($itemRefundTransfer, $pyzSalesOrderItemRefund);
        $pyzSalesOrderItemRefund->save();
    }
}
