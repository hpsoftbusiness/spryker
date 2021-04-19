<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\PriceProductStorage\Business;

use Pyz\Zed\PriceProductStorage\Business\Storage\Cte\PriceProductAbstract\PriceProductAbstractStorageMariaDbCte;
use Pyz\Zed\PriceProductStorage\Business\Storage\Cte\PriceProductConcrete\PriceProductConcreteStorageMariaDbCte;
use Pyz\Zed\PriceProductStorage\Business\Storage\Cte\PriceProductStorageCteInterface;
use Pyz\Zed\PriceProductStorage\Business\Storage\PriceProductAbstractStorageWriter;
use Pyz\Zed\PriceProductStorage\Business\Storage\PriceProductConcreteStorageWriter;
use Pyz\Zed\PriceProductStorage\PriceProductStorageDependencyProvider;
use Spryker\Client\Queue\QueueClientInterface;
use Spryker\Service\Synchronization\SynchronizationServiceInterface;
use Spryker\Zed\PriceProductStorage\Business\PriceProductStorageBusinessFactory as SprykerPriceProductStorageBusinessFactory;
use Spryker\Zed\PriceProductStorage\Business\Storage\PriceProductAbstractStorageWriterInterface;
use Spryker\Zed\PriceProductStorage\Business\Storage\PriceProductConcreteStorageWriterInterface;

/**
 * @method \Pyz\Zed\PriceProductStorage\PriceProductStorageConfig getConfig()
 */
class PriceProductStorageBusinessFactory extends SprykerPriceProductStorageBusinessFactory
{
    /**
     * @return \Spryker\Zed\PriceProductStorage\Business\Storage\PriceProductAbstractStorageWriterInterface
     */
    public function createPriceProductAbstractStorageWriter(): PriceProductAbstractStorageWriterInterface
    {
        return new PriceProductAbstractStorageWriter(
            $this->getPriceProductFacade(),
            $this->getStoreFacade(),
            $this->getQueryContainer(),
            $this->getConfig()->isSendingToQueue(),
            $this->getSynchronizationService(),
            $this->getQueueClient()
        );
    }

    /**
     * @return \Spryker\Zed\PriceProductStorage\Business\Storage\PriceProductConcreteStorageWriterInterface
     */
    public function createPriceProductConcreteStorageWriter(): PriceProductConcreteStorageWriterInterface
    {
        return new PriceProductConcreteStorageWriter(
            $this->getPriceProductFacade(),
            $this->getStoreFacade(),
            $this->getQueryContainer(),
            $this->getConfig()->isSendingToQueue(),
            $this->getSynchronizationService(),
            $this->getQueueClient()
        );
    }

    /**
     * @return \Pyz\Zed\PriceProductStorage\Business\Storage\Cte\PriceProductStorageCteInterface
     */
    public function createPriceProductAbstractStorageMariaDbCte(): PriceProductStorageCteInterface
    {
        return new PriceProductAbstractStorageMariaDbCte();
    }

    /**
     * @return \Pyz\Zed\PriceProductStorage\Business\Storage\Cte\PriceProductStorageCteInterface
     */
    public function createPriceProductConcreteStorageMariaDbCte(): PriceProductStorageCteInterface
    {
        return new PriceProductConcreteStorageMariaDbCte();
    }

    /**
     * @return \Spryker\Service\Synchronization\SynchronizationServiceInterface
     */
    public function getSynchronizationService(): SynchronizationServiceInterface
    {
        return $this->getProvidedDependency(PriceProductStorageDependencyProvider::SERVICE_SYNCHRONIZATION);
    }

    /**
     * @return \Spryker\Client\Queue\QueueClientInterface
     */
    public function getQueueClient(): QueueClientInterface
    {
        return $this->getProvidedDependency(PriceProductStorageDependencyProvider::CLIENT_QUEUE);
    }
}
