<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\UrlStorage\Business;

use Pyz\Zed\UrlStorage\Business\Storage\Cte\UrlStorageCteInterface;
use Pyz\Zed\UrlStorage\Business\Storage\Cte\UrlStorageMariaDbCte;
use Pyz\Zed\UrlStorage\Business\Storage\UrlStorageWriter;
use Pyz\Zed\UrlStorage\UrlStorageDependencyProvider;
use Spryker\Client\Queue\QueueClientInterface;
use Spryker\Service\Synchronization\SynchronizationServiceInterface;
use Spryker\Zed\UrlStorage\Business\Storage\UrlStorageWriterInterface;
use Spryker\Zed\UrlStorage\Business\UrlStorageBusinessFactory as SprykerUrlStorageBusinessFactory;

/**
 * @method \Pyz\Zed\UrlStorage\UrlStorageConfig getConfig()
 */
class UrlStorageBusinessFactory extends SprykerUrlStorageBusinessFactory
{
    /**
     * @return \Spryker\Zed\UrlStorage\Business\Storage\UrlStorageWriterInterface
     */
    public function createUrlStorageWriter(): UrlStorageWriterInterface
    {
        return new UrlStorageWriter(
            $this->getUtilSanitizeService(),
            $this->getRepository(),
            $this->getEntityManager(),
            $this->getStoreFacade(),
            $this->getConfig()->isSendingToQueue(),
            $this->getSynchronizationService(),
            $this->getQueueClient(),
            $this->createUrlStorageMariaDbCte()
        );
    }

    /**
     * @return \Pyz\Zed\UrlStorage\Business\Storage\Cte\UrlStorageCteInterface
     */
    public function createUrlStorageMariaDbCte(): UrlStorageCteInterface
    {
        return new UrlStorageMariaDbCte();
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
