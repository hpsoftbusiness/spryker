<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Sales\Business\Customer;

use ArrayObject;
use Generated\Shared\Transfer\OrderListTransfer;
use Orm\Zed\Sales\Persistence\Map\SpySalesOrderTableMap;
use Spryker\Shared\Kernel\Store;
use Spryker\Zed\Sales\Business\Model\Customer\PaginatedCustomerOrderOverview as SprykerPaginatedCustomerOrderOverview;
use Spryker\Zed\Sales\Business\Model\Order\CustomerOrderOverviewHydratorInterface;
use Spryker\Zed\Sales\Dependency\Facade\SalesToOmsInterface;
use Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface;

class PaginatedCustomerOrderOverview extends SprykerPaginatedCustomerOrderOverview
{
    /**
     * @var \Spryker\Shared\Kernel\Store
     */
    private $store;

    /**
     * @param \Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\Sales\Business\Model\Order\CustomerOrderOverviewHydratorInterface $customerOrderOverviewHydrator
     * @param \Spryker\Zed\Sales\Dependency\Facade\SalesToOmsInterface $omsFacade
     * @param array $searchOrderExpanderPlugins
     * @param \Spryker\Shared\Kernel\Store $store
     */
    public function __construct(
        SalesQueryContainerInterface $queryContainer,
        CustomerOrderOverviewHydratorInterface $customerOrderOverviewHydrator,
        SalesToOmsInterface $omsFacade,
        array $searchOrderExpanderPlugins,
        Store $store
    ) {
        parent::__construct($queryContainer, $customerOrderOverviewHydrator, $omsFacade, $searchOrderExpanderPlugins);
        $this->store = $store;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderListTransfer $orderListTransfer
     * @param int $idCustomer
     *
     * @return \Generated\Shared\Transfer\OrderListTransfer
     */
    public function getOrdersOverview(OrderListTransfer $orderListTransfer, $idCustomer): OrderListTransfer
    {
        $ordersQuery = $this->queryContainer->queryCustomerOrders(
            $idCustomer,
            $orderListTransfer->getFilter()
        );
        $ordersQuery->filterByStore($this->store->getStoreName());

        if (!$ordersQuery->getOrderByColumns()) {
            $ordersQuery->addDescendingOrderByColumn(SpySalesOrderTableMap::COL_CREATED_AT);
        }

        $orderCollection = $this->getOrderCollection($orderListTransfer, $ordersQuery);

        $orders = new ArrayObject();
        foreach ($orderCollection as $salesOrderEntity) {
            if ($salesOrderEntity->countItems() === 0) {
                continue;
            }

            if ($this->excludeOrder($salesOrderEntity)) {
                continue;
            }

            $orderTransfer = $this->customerOrderOverviewHydrator->hydrateOrderTransfer($salesOrderEntity);
            $orders->append($orderTransfer);
        }

        $orderTransfers = $this->executeSearchOrderExpanderPlugins($orders->getArrayCopy());
        $orderListTransfer->setOrders(new ArrayObject($orderTransfers));

        return $orderListTransfer;
    }
}
