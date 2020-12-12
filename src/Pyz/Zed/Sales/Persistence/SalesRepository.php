<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Sales\Persistence;

use Generated\Shared\Transfer\SalesOrderFilterTransfer;
use Orm\Zed\Sales\Persistence\Map\SpySalesOrderTableMap;
use Orm\Zed\Sales\Persistence\SpySalesOrderQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Sales\Persistence\SalesRepository as SprykerSalesRepository;

/**
 * @method \Pyz\Zed\Sales\Persistence\SalesPersistenceFactory getFactory()
 */
class SalesRepository extends SprykerSalesRepository implements SalesRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\SalesOrderFilterTransfer $salesOrderFilterTransfer
     *
     * @return int[]
     */
    public function getOrderIdsBySalesOrderFilter(SalesOrderFilterTransfer $salesOrderFilterTransfer): array
    {
        $salesOrderQuery = $this->applyOrdersFilterToSalesQuery(
            $this->getFactory()->createSalesOrderQuery(),
            $salesOrderFilterTransfer
        );

        return $salesOrderQuery->select(SpySalesOrderTableMap::COL_ID_SALES_ORDER)
            ->find()
            ->toArray();
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
