<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Zed\Refund\Helper;

use Codeception\Module;
use Generated\Shared\Transfer\ExpenseRefundTransfer;
use Generated\Shared\Transfer\ItemRefundTransfer;
use Orm\Zed\Refund\Persistence\PyzSalesExpenseRefund;
use Orm\Zed\Refund\Persistence\PyzSalesExpenseRefundQuery;
use Orm\Zed\Refund\Persistence\PyzSalesOrderItemRefund;
use Orm\Zed\Refund\Persistence\PyzSalesOrderItemRefundQuery;

class BusinessDataHelper extends Module
{
    /**
     * @param \Generated\Shared\Transfer\ItemRefundTransfer $itemRefundTransfer
     *
     * @return \Orm\Zed\Refund\Persistence\PyzSalesOrderItemRefund
     */
    public function haveOrderItemRefundEntity(ItemRefundTransfer $itemRefundTransfer): PyzSalesOrderItemRefund
    {
        $orderItemRefundEntity = PyzSalesOrderItemRefundQuery::create()
            ->filterByFkSalesPayment($itemRefundTransfer->getFkSalesPayment())
            ->filterByFkSalesOrderItem($itemRefundTransfer->getFkSalesOrderItem())
            ->findOneOrCreate();
        $orderItemRefundEntity->setStatus($itemRefundTransfer->getStatus());
        $orderItemRefundEntity->setAmount($itemRefundTransfer->getAmount());
        $orderItemRefundEntity->save();

        return $orderItemRefundEntity;
    }

    /**
     * @param \Generated\Shared\Transfer\ExpenseRefundTransfer $expenseRefundTransfer
     *
     * @return \Orm\Zed\Refund\Persistence\PyzSalesExpenseRefund
     */
    public function haveSalesExpenseRefundEntity(ExpenseRefundTransfer $expenseRefundTransfer): PyzSalesExpenseRefund
    {
        $salesExpenseRefundEntity = PyzSalesExpenseRefundQuery::create()
            ->filterByFkSalesExpense($expenseRefundTransfer->getFkSalesExpense())
            ->filterByFkSalesPayment($expenseRefundTransfer->getFkSalesPayment())
            ->findOneOrCreate();
        $salesExpenseRefundEntity->setStatus($expenseRefundTransfer->getStatus());
        $salesExpenseRefundEntity->setAmount($expenseRefundTransfer->getAmount());
        $salesExpenseRefundEntity->save();

        return $salesExpenseRefundEntity;
    }
}
