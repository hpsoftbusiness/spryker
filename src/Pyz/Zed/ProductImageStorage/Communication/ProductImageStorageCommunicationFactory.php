<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductImageStorage\Communication;

use Pyz\Zed\ProductImageStorage\ProductImageStorageDependencyProvider;
use Spryker\Zed\Event\Business\EventFacadeInterface;
use Spryker\Zed\ProductImageStorage\Communication\ProductImageStorageCommunicationFactory as SprykerProductImageStorageCommunicationFactory;

class ProductImageStorageCommunicationFactory extends SprykerProductImageStorageCommunicationFactory
{
    /**
     * @return \Spryker\Zed\Event\Business\EventFacadeInterface
     */
    public function getEventFacade(): EventFacadeInterface
    {
        return $this->getProvidedDependency(ProductImageStorageDependencyProvider::FACADE_EVENT);
    }
}
