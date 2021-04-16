<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductStorage\Communication;

use Pyz\Zed\UrlStorage\UrlStorageDependencyProvider;
use Spryker\Zed\Event\Business\EventFacadeInterface;
use Spryker\Zed\ProductStorage\Communication\ProductStorageCommunicationFactory as SprykerProductStorageCommunicationFactory;

class ProductStorageCommunicationFactory extends SprykerProductStorageCommunicationFactory
{
    /**
     * @return \Spryker\Zed\Event\Business\EventFacadeInterface
     */
    public function getEventFacade(): EventFacadeInterface
    {
        return $this->getProvidedDependency(UrlStorageDependencyProvider::FACADE_EVENT);
    }
}
