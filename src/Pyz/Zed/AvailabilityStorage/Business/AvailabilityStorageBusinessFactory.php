<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\AvailabilityStorage\Business;

use Pyz\Zed\AvailabilityStorage\Business\Storage\Cte\AvailabilityStorageCteInterface;
use Pyz\Zed\AvailabilityStorage\Business\Storage\Cte\AvailabilityStorageMariaDbCte;
use Pyz\Zed\AvailabilityStorage\Business\Storage\Cte\AvailabilityStoragePgDbCte;
use Pyz\Zed\UrlStorage\UrlStorageDependencyProvider;
use Spryker\Client\Queue\QueueClientInterface;
use Spryker\Service\Synchronization\SynchronizationServiceInterface;
use Spryker\Zed\AvailabilityStorage\Business\AvailabilityStorageBusinessFactory as SprykerAvailabilityStorageBusinessFactory;

class AvailabilityStorageBusinessFactory extends SprykerAvailabilityStorageBusinessFactory
{
//    /**
//     * @return \Spryker\Zed\AvailabilityStorage\Business\Storage\AvailabilityStorageInterface
//     */
//    public function createAvailabilityStorage(): AvailabilityStorageInterface
//    {
//        return new AvailabilityStorage(
//            $this->getStore(),
//            $this->getQueryContainer(),
//            $this->getConfig()->isSendingToQueue(),
//            $this->getRepository(),
//            $this->getSynchronizationService(),
//            $this->getQueueClient(),
//            $this->createAvailabilityStoragePgDbCte()
//        );
//    }

    /**
     * @return \Pyz\Zed\AvailabilityStorage\Business\Storage\Cte\AvailabilityStorageCteInterface
     */
    public function createAvailabilityStorageMariaDbCte(): AvailabilityStorageCteInterface
    {
        return new AvailabilityStorageMariaDbCte();
    }

    /**
     * @return \Pyz\Zed\AvailabilityStorage\Business\Storage\Cte\AvailabilityStorageCteInterface
     */
    public function createAvailabilityStoragePgDbCte(): AvailabilityStorageCteInterface
    {
        return new AvailabilityStoragePgDbCte();
    }

    /**
     * @return \Spryker\Service\Synchronization\SynchronizationServiceInterface
     */
    public function getSynchronizationService(): SynchronizationServiceInterface
    {
        return $this->getProvidedDependency(UrlStorageDependencyProvider::SERVICE_SYNCHRONIZATION);
    }

    /**
     * @return \Spryker\Client\Queue\QueueClientInterface
     */
    public function getQueueClient(): QueueClientInterface
    {
        return $this->getProvidedDependency(UrlStorageDependencyProvider::CLIENT_QUEUE);
    }
}
