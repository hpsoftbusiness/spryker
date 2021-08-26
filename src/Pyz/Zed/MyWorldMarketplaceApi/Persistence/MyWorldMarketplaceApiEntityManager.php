<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MyWorldMarketplaceApi\Persistence;

use Orm\Zed\Sales\Persistence\Base\SpySalesOrderItem;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Pyz\Zed\MyWorldMarketplaceApi\Persistence\MyWorldMarketplaceApiPersistenceFactory getFactory()
 */
class MyWorldMarketplaceApiEntityManager extends AbstractEntityManager implements MyWorldMarketplaceApiEntityManagerInterface
{
    /**
     * @param int $orderItemId
     *
     * @return void
     */
    public function setTurnoverCreated(int $orderItemId): void
    {
        $this->findOrderItemById($orderItemId)
            ->setIsTurnoverCreated(true)
            ->save();
    }

    /**
     * @param int $orderItemId
     *
     * @return void
     */
    public function setTurnoverCancelled(int $orderItemId): void
    {
        $this->findOrderItemById($orderItemId)
            ->setIsTurnoverCancelled(true)
            ->save();
    }

    /**
     * @param int $orderItemId
     * @param string $turnoverReference
     *
     * @return void
     */
    public function updateTurnoverReference(int $orderItemId, string $turnoverReference): void
    {
        $this->findOrderItemById($orderItemId)
            ->setTurnoverReference($turnoverReference)
            ->save();
    }

    /**
     * @param int $orderItemId
     *
     * @return \Orm\Zed\Sales\Persistence\Base\SpySalesOrderItem
     */
    protected function findOrderItemById(int $orderItemId): SpySalesOrderItem
    {
        return $this->getFactory()
            ->getSalesOrderItemPropelQuery()
            ->filterByIdSalesOrderItem($orderItemId)
            ->find()
            ->getFirst();
    }
}
