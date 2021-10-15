<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Weclapp\Communication\Plugin\Event\Listener;

use Orm\Zed\Category\Persistence\Map\SpyCategoryNodeTableMap;
use Spryker\Zed\Event\Dependency\Plugin\EventBulkHandlerInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Pyz\Zed\Weclapp\Communication\WeclappCommunicationFactory getFactory()
 * @method \Pyz\Zed\Weclapp\Business\WeclappFacadeInterface getFacade()
 * @method \Pyz\Zed\Weclapp\WeclappConfig getConfig()
 */
class WeclappCategoryNodeExportBulkListener extends AbstractPlugin implements EventBulkHandlerInterface
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
        $categoriesIds = $this->getFactory()
            ->getEventBehaviorFacade()
            ->getEventTransferForeignKeys($transfers, SpyCategoryNodeTableMap::COL_FK_CATEGORY);
        $this->getFacade()->exportCategories($categoriesIds);
    }
}
