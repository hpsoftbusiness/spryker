<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Weclapp\Communication\Plugin\Event\Listener;

use Orm\Zed\Product\Persistence\Map\SpyProductLocalizedAttributesTableMap;
use Spryker\Zed\Event\Dependency\Plugin\EventBulkHandlerInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Pyz\Zed\Weclapp\Communication\WeclappCommunicationFactory getFactory()
 * @method \Pyz\Zed\Weclapp\Business\WeclappFacadeInterface getFacade()
 * @method \Pyz\Zed\Weclapp\WeclappConfig getConfig()
 */
class WeclappProductLocalizedAttributesExportBulkListener extends AbstractPlugin implements EventBulkHandlerInterface
{
    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $transfers
     * @param string $eventName
     *
     * @return void
     */
    public function handleBulk(array $transfers, $eventName): void
    {
        $productsIds = $this->getFactory()
            ->getEventBehaviorFacade()
            ->getEventTransferForeignKeys($transfers, SpyProductLocalizedAttributesTableMap::COL_FK_PRODUCT);
        $this->getFacade()->exportProducts($productsIds);
    }
}
