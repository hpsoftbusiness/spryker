<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\AvailabilityGui\Communication\Table;

use Orm\Zed\Availability\Persistence\Map\SpyAvailabilityAbstractTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Spryker\Zed\Availability\Persistence\AvailabilityQueryContainer;
use Spryker\Zed\AvailabilityGui\Communication\Helper\AvailabilityHelperInterface;
use Spryker\Zed\AvailabilityGui\Communication\Table\AvailabilityAbstractTable as SprykerAvailabilityAbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class AvailabilityAbstractTable extends SprykerAvailabilityAbstractTable
{
    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array
     */
    protected function prepareData(TableConfiguration $config): array
    {
        $result = [];

        $this->expandPropelQuery();

        /** @var \Orm\Zed\Product\Persistence\Base\SpyProductAbstract[]|\Propel\Runtime\Collection\ObjectCollection $productAbstractEntities */
        $productAbstractEntities = $this->runQuery(
            $this->queryProductAbstractAvailability->filterByIsRemoved(false),
            $config,
            true
        );

        $productAbstractIds = $this->getProductAbstractIds($productAbstractEntities);
        $productAbstractEntities = $this->availabilityHelper
            ->getProductAbstractEntitiesWithStockByProductAbstractIds(
                $productAbstractIds,
                $this->idLocale,
                $this->idStore
            );

        foreach ($productAbstractEntities as $productAbstractEntity) {
            $haveBundledProducts = $this->haveBundledProducts($productAbstractEntity);

            $isNeverOutOfStock = $this->isNeverOutOfStock($productAbstractEntity);

            $result[] = [
                SpyProductAbstractTableMap::COL_SKU => $this->getProductEditPageLink(
                    $productAbstractEntity->getSku(),
                    $productAbstractEntity->getIdProductAbstract()
                ),
                AvailabilityQueryContainer::PRODUCT_NAME => $productAbstractEntity->getVirtualColumn(
                    AvailabilityHelperInterface::PRODUCT_NAME
                ),
                SpyAvailabilityAbstractTableMap::COL_QUANTITY => $this->getAvailabilityLabel(
                    $productAbstractEntity,
                    $isNeverOutOfStock
                ),
                AvailabilityHelperInterface::STOCK_QUANTITY => $this->getStockQuantity($productAbstractEntity)->trim(),
                AvailabilityHelperInterface::RESERVATION_QUANTITY => ($haveBundledProducts) ? 'N/A' : $this->calculateReservation(
                    $productAbstractEntity
                )->trim(),
                static::IS_BUNDLE_PRODUCT => $this->generateLabel($haveBundledProducts ? 'Yes' : 'No', null),
                AvailabilityHelperInterface::CONCRETE_NEVER_OUT_OF_STOCK_SET => $this->generateLabel(
                    $isNeverOutOfStock ? 'Yes' : 'No',
                    null
                ),
                static::TABLE_COL_ACTION => $this->createViewButton($productAbstractEntity),
            ];
        }

        return $result;
    }
}
