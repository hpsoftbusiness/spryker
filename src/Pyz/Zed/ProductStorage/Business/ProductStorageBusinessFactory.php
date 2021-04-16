<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductStorage\Business;

use Pyz\Zed\ProductStorage\Business\Storage\Cte\ProductAbstractStorageMariaDbCte;
use Pyz\Zed\ProductStorage\Business\Storage\Cte\ProductAbstractStoragePostgresCte;
use Pyz\Zed\ProductStorage\Business\Storage\Cte\ProductConcreteStorageMariaDbCte;
use Pyz\Zed\ProductStorage\Business\Storage\Cte\ProductConcreteStoragePostgresCte;
use Pyz\Zed\ProductStorage\Business\Storage\Cte\ProductStorageCteStrategyInterface;
use Pyz\Zed\ProductStorage\ProductStorageDependencyProvider;
use Spryker\Client\Queue\QueueClientInterface;
use Spryker\Service\Synchronization\SynchronizationServiceInterface;
use Spryker\Zed\ProductStorage\Business\Attribute\AttributeMap;
use Spryker\Zed\ProductStorage\Business\ProductStorageBusinessFactory as SprykerProductStorageBusinessFactory;

/**
 * @method \Spryker\Zed\ProductStorage\ProductStorageConfig getConfig()
 * @method \Spryker\Zed\ProductStorage\Persistence\ProductStorageQueryContainerInterface getQueryContainer()
 */
class ProductStorageBusinessFactory extends SprykerProductStorageBusinessFactory
{
//    /**
//     * @return \Spryker\Zed\ProductStorage\Business\Storage\ProductAbstractStorageWriterInterface
//     */
//    public function createProductAbstractStorageWriter()
//    {
//        return new ProductAbstractStorageWriter(
//            $this->getProductFacade(),
//            $this->createAttributeMap(),
//            $this->getQueryContainer(),
//            $this->getStoreFacade(),
//            $this->getConfig()->isSendingToQueue(),
//            $this->getProductAbstractStorageExpanderPlugins(),
//            $this->getSynchronizationService(),
//            $this->getQueueClient(),
//            $this->createProductAbstractStoragePgDbCte()
//        );
//    }

    /**
     * @return \Pyz\Zed\ProductStorage\Business\Storage\Cte\ProductStorageCteStrategyInterface
     */
    public function createProductAbstractStorageMariaDbCte(): ProductStorageCteStrategyInterface
    {
        return new ProductAbstractStorageMariaDbCte();
    }

    /**
     * @return \Pyz\Zed\ProductStorage\Business\Storage\Cte\ProductStorageCteStrategyInterface
     */
    public function createProductAbstractStoragePgDbCte(): ProductStorageCteStrategyInterface
    {
        return new ProductAbstractStoragePostgresCte();
    }

//    /**
//     * @return \Spryker\Zed\ProductStorage\Business\Storage\ProductConcreteStorageWriterInterface
//     */
//    public function createProductConcreteStorageWriter()
//    {
//        return new ProductConcreteStorageWriter(
//            $this->getProductFacade(),
//            $this->getQueryContainer(),
//            $this->getConfig()->isSendingToQueue(),
//            $this->getSynchronizationService(),
//            $this->getQueueClient(),
//            $this->createProductConcreteStoragePgDbCte()
//        );
//    }

    /**
     * @return \Pyz\Zed\ProductStorage\Business\Storage\Cte\ProductStorageCteStrategyInterface
     */
    public function createProductConcreteStorageMariaDbCte(): ProductStorageCteStrategyInterface
    {
        return new ProductConcreteStorageMariaDbCte();
    }

    /**
     * @return \Pyz\Zed\ProductStorage\Business\Storage\Cte\ProductStorageCteStrategyInterface
     */
    public function createProductConcreteStoragePgDbCte(): ProductStorageCteStrategyInterface
    {
        return new ProductConcreteStoragePostgresCte();
    }

    /**
     * @return \Spryker\Zed\ProductStorage\Business\Attribute\AttributeMapInterface
     */
    protected function createAttributeMap()
    {
        return new AttributeMap(
            $this->getProductFacade(),
            $this->getQueryContainer(),
            $this->getConfig(),
            $this->createSingleValueSuperAttributeFilter()
        );
    }

    /**
     * @return \Spryker\Zed\ProductStorage\Dependency\Facade\ProductStorageToProductBridge
     */
    protected function getProductFacade()
    {
        return $this->getProvidedDependency(ProductStorageDependencyProvider::FACADE_PRODUCT);
    }

    /**
     * @return \Spryker\Service\Synchronization\SynchronizationServiceInterface
     */
    public function getSynchronizationService(): SynchronizationServiceInterface
    {
        return $this->getProvidedDependency(ProductStorageDependencyProvider::SERVICE_SYNCHRONIZATION);
    }

    /**
     * @return \Spryker\Client\Queue\QueueClientInterface
     */
    public function getQueueClient(): QueueClientInterface
    {
        return $this->getProvidedDependency(ProductStorageDependencyProvider::CLIENT_QUEUE);
    }
}
