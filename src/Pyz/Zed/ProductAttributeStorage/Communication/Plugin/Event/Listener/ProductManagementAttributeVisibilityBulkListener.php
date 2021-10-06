<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductAttributeStorage\Communication\Plugin\Event\Listener;

use Spryker\Zed\Event\Dependency\Plugin\EventBulkHandlerInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Pyz\Zed\ProductAttributeStorage\Business\ProductAttributeStorageFacadeInterface getFacade()
 * @method \Pyz\Zed\ProductAttributeStorage\ProductAttributeStorageConfig getConfig()
 * @method \Pyz\Zed\ProductAttributeStorage\Communication\ProductAttributeStorageCommunicationFactory getFactory()
 */
class ProductManagementAttributeVisibilityBulkListener extends AbstractPlugin implements EventBulkHandlerInterface
{
    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $transfers
     * @param string $eventName
     *
     * @return void
     */
    public function handleBulk(array $transfers, $eventName)
    {
        $this->getFacade()->publishProductManagementAttributeVisibility();
    }
}
