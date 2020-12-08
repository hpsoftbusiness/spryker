<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Sales\Business\Order;

use Generated\Shared\Transfer\CommentTransfer;
use Generated\Shared\Transfer\OrderDetailsCommentsTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Spryker\Zed\Sales\Business\Order\OrderHydrator as SprykerOrderHydrator;

class OrderHydrator extends SprykerOrderHydrator
{
    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function applyOrderTransferHydrators(SpySalesOrder $orderEntity)
    {
        $orderTransfer = $this->hydrateBaseOrderTransfer($orderEntity);

        $this->hydrateOrderComments($orderEntity, $orderTransfer);
        $this->hydrateOrderTotals($orderEntity, $orderTransfer);
        $this->hydrateOrderItemsToOrderTransfer($orderEntity, $orderTransfer);
        $this->hydrateBillingAddressToOrderTransfer($orderEntity, $orderTransfer);
        $this->hydrateShippingAddressToOrderTransfer($orderEntity, $orderTransfer);
        $this->hydrateExpensesToOrderTransfer($orderEntity, $orderTransfer);
        $this->hydrateMissingCustomer($orderEntity, $orderTransfer);

        $orderTransfer->setTotalOrderCount(0);
        if ($orderTransfer->getCustomerReference()) {
            $customerReference = $orderTransfer->getCustomerReference();
            $totalCustomerOrderCount = $this->getTotalCustomerOrderCount($customerReference);
            $orderTransfer->setTotalOrderCount($totalCustomerOrderCount);
        }

        $uniqueProductQuantity = (int)$this->queryContainer
            ->queryCountUniqueProductsForOrder($orderEntity->getIdSalesOrder())
            ->count();

        $orderTransfer->setUniqueProductQuantity($uniqueProductQuantity);
        $orderTransfer = $this->executeHydrateOrderPlugins($orderTransfer);

        return $orderTransfer;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    protected function hydrateOrderComments(SpySalesOrder $orderEntity, OrderTransfer $orderTransfer): void
    {
        $salesOrderCommentEntities = $orderEntity->getOrderComments();

        if ($salesOrderCommentEntities->isEmpty()) {
            return;
        }

        $orderDetailsCommentsTransfer = new OrderDetailsCommentsTransfer();

        foreach ($salesOrderCommentEntities as $salesOrderCommentEntity) {
            $comment = (new CommentTransfer())->fromArray($salesOrderCommentEntity->toArray(), true);
            $orderDetailsCommentsTransfer->addComment($comment);
        }

        $orderTransfer->setOrderDetailsComments($orderDetailsCommentsTransfer);
    }
}
