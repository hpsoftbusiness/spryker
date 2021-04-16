<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\AvailabilityStorage\Communication;

use Pyz\Zed\AvailabilityStorage\AvailabilityStorageDependencyProvider;
use Spryker\Zed\AvailabilityStorage\Communication\AvailabilityStorageCommunicationFactory as SprykerAvailabilityStorageCommunicationFactory;
use Spryker\Zed\Event\Business\EventFacadeInterface;

class AvailabilityStorageCommunicationFactory extends SprykerAvailabilityStorageCommunicationFactory
{
    /**
     * @return \Spryker\Zed\Event\Business\EventFacadeInterface
     */
    public function getEventFacade(): EventFacadeInterface
    {
        return $this->getProvidedDependency(AvailabilityStorageDependencyProvider::FACADE_EVENT);
    }
}
