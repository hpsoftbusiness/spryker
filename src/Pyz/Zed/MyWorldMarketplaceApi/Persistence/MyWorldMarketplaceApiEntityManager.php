<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MyWorldMarketplaceApi\Persistence;

use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Pyz\Zed\MyWorldMarketplaceApi\Persistence\MyWorldMarketplaceApiPersistenceFactory getFactory()
 */
class MyWorldMarketplaceApiEntityManager extends AbstractEntityManager implements MyWorldMarketplaceApiEntityManagerInterface
{
    /**
     * @param int[] $orderItemIds
     * @param bool $isTurnoverCreated
     *
     * @return void
     */
    public function setIsTurnoverCreated(array $orderItemIds, bool $isTurnoverCreated = true): void
    {
        $salesOrderItemQuery = $this->getFactory()->getSalesOrderItemPropelQuery();
        $salesOrderItemEntities = $salesOrderItemQuery->filterByIdSalesOrderItem_In($orderItemIds)->find();

        foreach ($salesOrderItemEntities as $salesOrderItemEntity) {
            $salesOrderItemEntity->setIsTurnoverCreated($isTurnoverCreated);
            $salesOrderItemEntity->save();
        }
    }

    /**
     * @param int[] $orderItemIds
     * @param bool $isTurnoverCancelled
     *
     * @return void
     */
    public function setIsTurnoverCancelled(array $orderItemIds, bool $isTurnoverCancelled = true): void
    {
        $salesOrderItemQuery = $this->getFactory()->getSalesOrderItemPropelQuery();
        $salesOrderItemEntities = $salesOrderItemQuery->filterByIdSalesOrderItem_In($orderItemIds)->find();

        foreach ($salesOrderItemEntities as $salesOrderItemEntity) {
            $salesOrderItemEntity->setIsTurnoverCancelled($isTurnoverCancelled);
            $salesOrderItemEntity->save();
        }
    }
}
