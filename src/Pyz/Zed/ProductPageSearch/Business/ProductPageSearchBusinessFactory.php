<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductPageSearch\Business;

use Pyz\Zed\ProductPageSearch\Business\Attribute\ProductPageAttribute;
use Pyz\Zed\ProductPageSearch\Business\Mapper\ProductPageSearchMapper;
use Pyz\Zed\ProductPageSearch\Business\Publisher\ProductAbstractPagePublisher;
use Pyz\Zed\ProductPageSearch\Business\Publisher\ProductConcretePageSearchPublisher;
use Pyz\Zed\ProductPageSearch\Business\Publisher\Sql\ProductAbstractPageSearchMariaDbPagePublisherCte;
use Pyz\Zed\ProductPageSearch\Business\Publisher\Sql\ProductConcretePageSearchMariaDbPagePublisherCte;
use Pyz\Zed\ProductPageSearch\Business\Publisher\Sql\ProductPagePublisherCteInterface;
use Pyz\Zed\ProductPageSearch\ProductPageSearchDependencyProvider;
use Spryker\Client\Queue\QueueClientInterface;
use Spryker\Service\Synchronization\SynchronizationServiceInterface;
use Spryker\Zed\ProductPageSearch\Business\Attribute\ProductPageAttributeInterface;
use Spryker\Zed\ProductPageSearch\Business\DataMapper\AbstractProductSearchDataMapper;
use Spryker\Zed\ProductPageSearch\Business\DataMapper\ProductAbstractSearchDataMapper;
use Spryker\Zed\ProductPageSearch\Business\ProductPageSearchBusinessFactory as SprykerProductPageSearchBusinessFactory;
use Spryker\Zed\ProductPageSearch\Business\Publisher\ProductConcretePageSearchPublisherInterface;

class ProductPageSearchBusinessFactory extends SprykerProductPageSearchBusinessFactory
{
    /**
     * @return \Spryker\Zed\ProductPageSearch\Business\Mapper\ProductPageSearchMapperInterface
     */
    protected function createProductPageMapper()
    {
        return new ProductPageSearchMapper(
            $this->createProductPageAttribute(),
            $this->createProductAbstractSearchDataMapper(),
            $this->getUtilEncoding()
        );
    }

    /**
     * @return \Spryker\Zed\ProductPageSearch\Business\DataMapper\AbstractProductSearchDataMapper
     */
    public function createProductAbstractSearchDataMapper(): AbstractProductSearchDataMapper
    {
        return new ProductAbstractSearchDataMapper(
            $this->createPageMapBuilder(),
            $this->getSearchFacade(),
            $this->getProductSearchFacade(),
            $this->getProductAbstractMapExpanderPlugins()
        );
    }

    /**
     * @return \Pyz\Zed\ProductPageSearch\Dependency\Plugin\ProductAbstractPageAfterPublishPluginInterface[]
     */
    public function getProductAbstractPageAfterPublishPlugins(): array
    {
        return $this->getProvidedDependency(ProductPageSearchDependencyProvider::PLUGIN_PRODUCT_ABSTRACT_PAGE_AFTER_PUBLISH);
    }

    /**
     * @return \Spryker\Service\Synchronization\SynchronizationServiceInterface
     */
    public function getSynchronizationService(): SynchronizationServiceInterface
    {
        return $this->getProvidedDependency(ProductPageSearchDependencyProvider::SERVICE_SYNCHRONIZATION);
    }

    /**
     * @return \Spryker\Client\Queue\QueueClientInterface
     */
    public function getQueueClient(): QueueClientInterface
    {
        return $this->getProvidedDependency(ProductPageSearchDependencyProvider::CLIENT_QUEUE);
    }

    /**
     * @return \Pyz\Zed\ProductPageSearch\Business\Publisher\Sql\ProductPagePublisherCteInterface
     */
    public function createProductAbstractPageSearchMariaDbPublisherCte(): ProductPagePublisherCteInterface
    {
        return new ProductAbstractPageSearchMariaDbPagePublisherCte();
    }

    /**
     * @return \Pyz\Zed\ProductPageSearch\Business\Publisher\Sql\ProductPagePublisherCteInterface
     */
    public function createProductConcretePageSearchMariaDbPublisherCte(): ProductPagePublisherCteInterface
    {
        return new ProductConcretePageSearchMariaDbPagePublisherCte();
    }

    /**
     * @return \Spryker\Zed\ProductPageSearch\Business\Publisher\ProductAbstractPagePublisherInterface
     */
    public function createProductAbstractPagePublisher()
    {
        return new ProductAbstractPagePublisher(
            $this->getQueryContainer(),
            $this->getProductPageDataExpanderPlugins(),
            $this->getProductPageDataLoaderPlugins(),
            $this->createProductPageMapper(),
            $this->createProductPageWriter(),
            $this->getConfig(),
            $this->getStoreFacade(),
            $this->createAddToCartSkuReader(),
            $this->getProductAbstractPageAfterPublishPlugins(),
            $this->getSynchronizationService(),
            $this->getQueueClient()
        );
    }

    /**
     * @return \Spryker\Zed\ProductPageSearch\Business\Publisher\ProductConcretePageSearchPublisherInterface
     */
    public function createProductConcretePageSearchPublisher(): ProductConcretePageSearchPublisherInterface
    {
        return new ProductConcretePageSearchPublisher(
            $this->createProductConcretePageSearchReader(),
            $this->createProductConcretePageSearchWriter(),
            $this->getProductFacade(),
            $this->getUtilEncoding(),
            $this->createProductConcreteSearchDataMapper(),
            $this->getStoreFacade(),
            $this->getConfig(),
            $this->getProductConcretePageDataExpanderPlugins(),
            $this->getSynchronizationService(),
            $this->getQueueClient()
        );
    }

    /**
     * @return \Spryker\Zed\ProductPageSearch\Business\Attribute\ProductPageAttributeInterface
     */
    public function createProductPageAttribute(): ProductPageAttributeInterface
    {
        return new ProductPageAttribute(
            $this->getProductFacade()
        );
    }
}
