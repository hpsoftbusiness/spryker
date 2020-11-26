<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Sales\Persistence;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\SalesOrderFilterTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrderQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Sales\Persistence\SalesRepository as SprykerSalesRepository;

/**
 * @method \Pyz\Zed\Sales\Persistence\SalesPersistenceFactory getFactory()
 */
class SalesRepository extends SprykerSalesRepository implements SalesRepositoryInterface
{
    public const COLUMN_TRANSACTION = 'transaction';
    public const COLUMN_PAYMENT_METHOD = 'payment_method';

    /**
     * @param \Generated\Shared\Transfer\SalesOrderFilterTransfer $salesOrderFilterTransfer
     *
     * @return array
     */
    public function getOrdersBySalesOrderFilter(SalesOrderFilterTransfer $salesOrderFilterTransfer): array
    {
        $salesOrderQuery = $this->applyOrdersFilterToSalesQuery(
            $this->getFactory()->createSalesOrderQuery(),
            $salesOrderFilterTransfer
        );

        $salesOrderEntityCollection = $salesOrderQuery->find();

        $salesOrderMapper = $this->getFactory()
            ->createSalesOrderMapper();

        $orderListTransfer = [];
        foreach ($salesOrderEntityCollection as $salesOrderEntity) {
            $orderTransfer = $salesOrderMapper->mapSalesOrderEntityToOrderTransfer($salesOrderEntity, new OrderTransfer());
            $orderListTransfer[] = $orderTransfer;
        }

        return $orderListTransfer;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderQuery $spySalesOrderQuery
     * @param \Generated\Shared\Transfer\SalesOrderFilterTransfer $salesOrderFilterTransfer
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderQuery
     */
    protected function applyOrdersFilterToSalesQuery(
        SpySalesOrderQuery $spySalesOrderQuery,
        SalesOrderFilterTransfer $salesOrderFilterTransfer
    ): SpySalesOrderQuery {
        if ($salesOrderFilterTransfer->isPropertyModified(
            SalesOrderFilterTransfer::CREATED_FROM
        )) {
            $spySalesOrderQuery->filterByCreatedAt(
                $salesOrderFilterTransfer->getCreatedFrom(),
                Criteria::GREATER_EQUAL
            );
        }

        if ($salesOrderFilterTransfer->isPropertyModified(
            SalesOrderFilterTransfer::CREATED_TO
        )) {
            $spySalesOrderQuery->filterByCreatedAt(
                $salesOrderFilterTransfer->getCreatedTo(),
                Criteria::LESS_EQUAL
            );
        }

        return $spySalesOrderQuery;
    }
}
