<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Weclapp\Communication\Plugin\Event\Listener;

use Orm\Zed\ProductCategory\Persistence\Map\SpyProductCategoryTableMap;
use Spryker\Zed\Event\Dependency\Plugin\EventBulkHandlerInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Pyz\Zed\Weclapp\Communication\WeclappCommunicationFactory getFactory()
 * @method \Pyz\Zed\Weclapp\Business\WeclappFacadeInterface getFacade()
 * @method \Pyz\Zed\Weclapp\WeclappConfig getConfig()
 */
class WeclappProductCategoryExportBulkListener extends AbstractPlugin implements EventBulkHandlerInterface
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
        $productAbstractsIds = $this->getFactory()
            ->getEventBehaviorFacade()
            ->getEventTransferForeignKeys(
                $transfers,
                SpyProductCategoryTableMap::COL_FK_PRODUCT_ABSTRACT
            );
        $productsIds = $this->getFactory()
            ->getProductFacade()
            ->findProductConcreteIdsByAbstractProductIds($productAbstractsIds);

        $this->getFacade()->exportProducts($productsIds);
    }
}
