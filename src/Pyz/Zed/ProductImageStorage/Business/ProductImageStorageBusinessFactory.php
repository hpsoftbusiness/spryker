<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductImageStorage\Business;

use Pyz\Zed\ProductImageStorage\Business\Storage\Cte\ProductImageAbstractStorageMariaDbCte;
use Pyz\Zed\ProductImageStorage\Business\Storage\Cte\ProductImageConcreteStorageMariaDbCte;
use Pyz\Zed\ProductImageStorage\Business\Storage\Cte\ProductImageStorageCteInterface;
use Pyz\Zed\ProductImageStorage\Business\Storage\ProductAbstractImageStorageWriter;
use Pyz\Zed\ProductImageStorage\Business\Storage\ProductConcreteImageStorageWriter;
use Pyz\Zed\UrlStorage\UrlStorageDependencyProvider;
use Spryker\Client\Queue\QueueClientInterface;
use Spryker\Service\Synchronization\SynchronizationServiceInterface;
use Spryker\Zed\ProductImageStorage\Business\ProductImageStorageBusinessFactory as SprykerProductImageStorageBusinessFactory;

class ProductImageStorageBusinessFactory extends SprykerProductImageStorageBusinessFactory
{
   /**
    * @return \Spryker\Zed\ProductImageStorage\Business\Storage\ProductAbstractImageStorageWriterInterface
    */
    public function createProductAbstractImageWriter()
    {
        return new ProductAbstractImageStorageWriter(
            $this->getProductImageFacade(),
            $this->getQueryContainer(),
            $this->getRepository(),
            $this->getConfig()->isSendingToQueue(),
            $this->getSynchronizationService(),
            $this->getQueueClient(),
            $this->createProductAbstractImageStorageMariaDbCte()
        );
    }

    /**
     * @return \Spryker\Zed\ProductImageStorage\Business\Storage\ProductConcreteImageStorageWriterInterface
     */
    public function createProductConcreteImageWriter()
    {
        return new ProductConcreteImageStorageWriter(
            $this->getProductImageFacade(),
            $this->getQueryContainer(),
            $this->getRepository(),
            $this->getConfig()->isSendingToQueue(),
            $this->getSynchronizationService(),
            $this->getQueueClient(),
            $this->createProductConcreteImageStorageMariaDbCte()
        );
    }

    /**
     * @return \Pyz\Zed\ProductImageStorage\Business\Storage\Cte\ProductImageStorageCteInterface
     */
    public function createProductAbstractImageStorageMariaDbCte(): ProductImageStorageCteInterface
    {
        return new ProductImageAbstractStorageMariaDbCte();
    }

    /**
     * @return \Pyz\Zed\ProductImageStorage\Business\Storage\Cte\ProductImageStorageCteInterface
     */
    public function createProductConcreteImageStorageMariaDbCte(): ProductImageStorageCteInterface
    {
        return new ProductImageConcreteStorageMariaDbCte();
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
