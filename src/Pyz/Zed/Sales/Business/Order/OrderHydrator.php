<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Sales\Business\Order;

use ArrayObject;
use Generated\Shared\Transfer\CommentTransfer;
use Generated\Shared\Transfer\OrderDetailsCommentsTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Spryker\Zed\Sales\Business\Order\OrderHydrator as SprykerOrderHydrator;
use Spryker\Zed\Sales\Dependency\Facade\SalesToCustomerInterface;
use Spryker\Zed\Sales\Dependency\Facade\SalesToOmsInterface;
use Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface;
use Spryker\Zed\Sales\SalesConfig;

class OrderHydrator extends SprykerOrderHydrator
{
    /**
     * @var \Pyz\Zed\Sales\Dependency\Plugin\OrderExpenseExpanderPluginInterface[]
     */
    private $orderExpenseExpanderPlugins;

    /**
     * @param \Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\Sales\Dependency\Facade\SalesToOmsInterface $omsFacade
     * @param \Spryker\Zed\Sales\SalesConfig $salesConfig
     * @param \Spryker\Zed\Sales\Dependency\Facade\SalesToCustomerInterface $customerFacade
     * @param \Spryker\Zed\SalesExtension\Dependency\Plugin\OrderExpanderPluginInterface[] $hydrateOrderPlugins
     * @param \Spryker\Zed\SalesExtension\Dependency\Plugin\OrderItemExpanderPluginInterface[] $orderItemExpanderPlugins
     * @param \Spryker\Zed\SalesExtension\Dependency\Plugin\CustomerOrderAccessCheckPluginInterface[] $customerOrderAccessCheckPlugins
     * @param \Pyz\Zed\Sales\Dependency\Plugin\OrderExpenseExpanderPluginInterface[] $orderExpenseExpanderPlugins
     */
    public function __construct(
        SalesQueryContainerInterface $queryContainer,
        SalesToOmsInterface $omsFacade,
        SalesConfig $salesConfig,
        SalesToCustomerInterface $customerFacade,
        array $hydrateOrderPlugins = [],
        array $orderItemExpanderPlugins = [],
        array $customerOrderAccessCheckPlugins = [],
        array $orderExpenseExpanderPlugins = []
    ) {
        parent::__construct(
            $queryContainer,
            $omsFacade,
            $salesConfig,
            $customerFacade,
            $hydrateOrderPlugins,
            $orderItemExpanderPlugins,
            $customerOrderAccessCheckPlugins
        );

        $this->orderExpenseExpanderPlugins = $orderExpenseExpanderPlugins;
    }

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
    protected function hydrateExpensesToOrderTransfer(SpySalesOrder $orderEntity, OrderTransfer $orderTransfer)
    {
        parent::hydrateExpensesToOrderTransfer($orderEntity, $orderTransfer);

        $expenseTransfers = $this->expandOrderExpenses($orderTransfer->getExpenses()->getArrayCopy());
        $orderTransfer->setExpenses(new ArrayObject($expenseTransfers));
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

    /**
     * @param \Generated\Shared\Transfer\ExpenseTransfer[] $expenseTransfers
     *
     * @return \Generated\Shared\Transfer\ExpenseTransfer[]
     */
    private function expandOrderExpenses(array $expenseTransfers): array
    {
        foreach ($this->orderExpenseExpanderPlugins as $expanderPlugin) {
            $expenseTransfers = $expanderPlugin->expand($expenseTransfers);
        }

        return $expenseTransfers;
    }
}
