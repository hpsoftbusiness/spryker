<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Refund\Business;

use Generated\Shared\Transfer\ExpenseRefundTransfer;
use Generated\Shared\Transfer\ItemRefundTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Spryker\Zed\Refund\Business\RefundFacade as SprykerRefundFacade;

/**
 * @method \Pyz\Zed\Refund\Business\RefundBusinessFactory getFactory()
 * @method \Pyz\Zed\Refund\Persistence\RefundEntityManagerInterface getEntityManager()()
 */
class RefundFacade extends SprykerRefundFacade implements RefundFacadeInterface
{
    /**
     * @param int $idSalesOrder
     *
     * @return \Generated\Shared\Transfer\RefundDetailCollectionTransfer[]
     */
    public function getRefundDetailsByIdSalesOrder(int $idSalesOrder): array
    {
        return $this->getFactory()->createRefundDetailsCollector()->collectBySalesOrderId($idSalesOrder);
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem[] $orderItemEntities
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     *
     * @return void
     */
    public function processRefund(array $orderItemEntities, SpySalesOrder $orderEntity): void
    {
        $this->getFactory()->createRefundProcessor()->processRefund($orderItemEntities, $orderEntity);
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem[] $orderItemEntities
     * @param int $idSalesOrder
     *
     * @return void
     */
    public function validateRefund(array $orderItemEntities, int $idSalesOrder): void
    {
        $this->getFactory()->createRefundValidator()->validate($orderItemEntities, $idSalesOrder);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemRefundTransfer $itemRefundTransfer
     *
     * @return void
     */
    public function saveOrderItemRefund(ItemRefundTransfer $itemRefundTransfer): void
    {
        $this->getEntityManager()->saveSalesOrderItemRefund($itemRefundTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ExpenseRefundTransfer $expenseRefundTransfer
     *
     * @return void
     */
    public function saveOrderExpenseRefund(ExpenseRefundTransfer $expenseRefundTransfer): void
    {
        $this->getEntityManager()->saveSalesExpenseRefund($expenseRefundTransfer);
    }
}
