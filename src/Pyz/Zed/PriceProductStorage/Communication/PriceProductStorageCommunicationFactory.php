<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\PriceProductStorage\Communication;

use Pyz\Zed\PriceProductStorage\PriceProductStorageDependencyProvider;
use Spryker\Zed\Event\Business\EventFacadeInterface;
use Spryker\Zed\PriceProductStorage\Communication\PriceProductStorageCommunicationFactory as SprykerPriceProductStorageCommunicationFactory;

class PriceProductStorageCommunicationFactory extends SprykerPriceProductStorageCommunicationFactory
{
    /**
     * @return \Spryker\Zed\Event\Business\EventFacadeInterface
     */
    public function getEventFacade(): EventFacadeInterface
    {
        return $this->getProvidedDependency(PriceProductStorageDependencyProvider::FACADE_EVENT);
    }
}
