<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Stock\Persistence;

use Generated\Shared\Transfer\StockCriteriaFilterTransfer;
use Generated\Shared\Transfer\StockTransfer;
use Orm\Zed\Stock\Persistence\SpyStockQuery;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Stock\Persistence\StockRepository as SprykerStockRepository;

class StockRepository extends SprykerStockRepository implements StockRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\StockCriteriaFilterTransfer $stockCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\StockTransfer[]
     */
    public function getStocksByCriteriaFilter(StockCriteriaFilterTransfer $stockCriteriaFilterTransfer): array
    {
        $stockQuery = $this->getFactory()
            ->createStockQuery()
            ->leftJoinWithStockProduct()
            ->useStockProductQuery(null, Criteria::LEFT_JOIN)
                ->leftJoinWithSpyProduct()
            ->endUse();
        $stockQuery = $this->applyStockQueryFilters($stockQuery, $stockCriteriaFilterTransfer);

        return $this->getFactory()
            ->createStockMapper()
            ->mapStockEntitiesToStockTransfers($stockQuery->find()->getArrayCopy());
    }

    /**
     * @param \Orm\Zed\Stock\Persistence\SpyStockQuery $stockQuery
     * @param \Generated\Shared\Transfer\StockCriteriaFilterTransfer $stockCriteriaFilterTransfer
     *
     * @return \Orm\Zed\Stock\Persistence\SpyStockQuery
     */
    protected function applyStockQueryFilters(SpyStockQuery $stockQuery, StockCriteriaFilterTransfer $stockCriteriaFilterTransfer): SpyStockQuery
    {
        $stockQuery = parent::applyStockQueryFilters($stockQuery, $stockCriteriaFilterTransfer);

        if ($stockCriteriaFilterTransfer->getIsActive()) {
            $stockQuery->filterByIsActive(true);
        }

        if ($stockCriteriaFilterTransfer->getIdsProductConcrete()) {
            $stockQuery->useStockProductQuery(null, Criteria::LEFT_JOIN)
                ->filterByFkProduct_In($stockCriteriaFilterTransfer->getIdsProductConcrete())
                ->endUse();
        }

        return $stockQuery;
    }

    /**
     * @param string $idWeclappWarehouse
     *
     * @return \Generated\Shared\Transfer\StockTransfer|null
     */
    public function findStockByIdWeclappWarehouse(string $idWeclappWarehouse): ?StockTransfer
    {
        $stockEntity = $this->getFactory()
            ->createStockQuery()
            ->filterByIdWeclappWarehouse($idWeclappWarehouse)
            ->findOne();

        if ($stockEntity === null) {
            return null;
        }

        return $this->getFactory()
            ->createStockMapper()
            ->mapStockEntityToStockTransfer($stockEntity, new StockTransfer());
    }
}
