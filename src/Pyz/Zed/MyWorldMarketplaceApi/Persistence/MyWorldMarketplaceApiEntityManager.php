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
     * @param string $orderReference
     * @param bool $isTurnoverCreated
     *
     * @return void
     */
    public function setIsTurnoverCreated(string $orderReference, bool $isTurnoverCreated = true): void
    {
        $salesOrderQuery = $this->getFactory()->getSalesOrderPropelQuery();
        $salesOrderEntity = $salesOrderQuery->filterByOrderReference($orderReference)->findOne();

        if (!$salesOrderEntity) {
            return;
        }

        $salesOrderEntity->setIsTurnoverCreated($isTurnoverCreated);
        $salesOrderEntity->save();
    }

    /**
     * @param string $orderReference
     * @param bool $isTurnoverCancelled
     *
     * @return void
     */
    public function setIsTurnoverCancelled(string $orderReference, bool $isTurnoverCancelled = true): void
    {
        $salesOrderQuery = $this->getFactory()->getSalesOrderPropelQuery();
        $salesOrderEntity = $salesOrderQuery->filterByOrderReference($orderReference)->findOne();

        if (!$salesOrderEntity) {
            return;
        }

        $salesOrderEntity->setIsTurnoverCancelled($isTurnoverCancelled);
        $salesOrderEntity->save();
    }
}
