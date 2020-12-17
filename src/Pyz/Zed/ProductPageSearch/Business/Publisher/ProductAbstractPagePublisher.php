<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductPageSearch\Business\Publisher;

use Spryker\Zed\ProductPageSearch\Business\Mapper\ProductPageSearchMapperInterface;
use Spryker\Zed\ProductPageSearch\Business\Model\ProductPageSearchWriterInterface;
use Spryker\Zed\ProductPageSearch\Business\Publisher\ProductAbstractPagePublisher as SprykerProductAbstractPagePublisher;
use Spryker\Zed\ProductPageSearch\Business\Reader\AddToCartSkuReaderInterface;
use Spryker\Zed\ProductPageSearch\Dependency\Facade\ProductPageSearchToStoreFacadeInterface;
use Spryker\Zed\ProductPageSearch\Persistence\ProductPageSearchQueryContainerInterface;
use Spryker\Zed\ProductPageSearch\ProductPageSearchConfig;

class ProductAbstractPagePublisher extends SprykerProductAbstractPagePublisher
{
    /**
     * @var \Pyz\Zed\ProductPageSearch\Dependency\Plugin\ProductAbstractPageAfterPublishPluginInterface[]
     */
    protected $productAbstractPageAfterPublishPlugins;

    /**
     * @param \Spryker\Zed\ProductPageSearch\Persistence\ProductPageSearchQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\ProductPageSearch\Dependency\Plugin\ProductPageDataExpanderInterface[] $pageDataExpanderPlugins
     * @param \Spryker\Zed\ProductPageSearchExtension\Dependency\Plugin\ProductPageDataLoaderPluginInterface[] $productPageDataLoaderPlugins
     * @param \Spryker\Zed\ProductPageSearch\Business\Mapper\ProductPageSearchMapperInterface $productPageSearchMapper
     * @param \Spryker\Zed\ProductPageSearch\Business\Model\ProductPageSearchWriterInterface $productPageSearchWriter
     * @param \Spryker\Zed\ProductPageSearch\ProductPageSearchConfig $productPageSearchConfig
     * @param \Spryker\Zed\ProductPageSearch\Dependency\Facade\ProductPageSearchToStoreFacadeInterface $storeFacade
     * @param \Spryker\Zed\ProductPageSearch\Business\Reader\AddToCartSkuReaderInterface $addToCartSkuReader
     * @param \Pyz\Zed\ProductPageSearch\Dependency\Plugin\ProductAbstractPageAfterPublishPluginInterface[] $productAbstractPageAfterPublishPlugins
     */
    public function __construct(
        ProductPageSearchQueryContainerInterface $queryContainer,
        array $pageDataExpanderPlugins,
        array $productPageDataLoaderPlugins,
        ProductPageSearchMapperInterface $productPageSearchMapper,
        ProductPageSearchWriterInterface $productPageSearchWriter,
        ProductPageSearchConfig $productPageSearchConfig,
        ProductPageSearchToStoreFacadeInterface $storeFacade,
        AddToCartSkuReaderInterface $addToCartSkuReader,
        array $productAbstractPageAfterPublishPlugins
    ) {
        parent::__construct(
            $queryContainer,
            $pageDataExpanderPlugins,
            $productPageDataLoaderPlugins,
            $productPageSearchMapper,
            $productPageSearchWriter,
            $productPageSearchConfig,
            $storeFacade,
            $addToCartSkuReader
        );

        $this->productAbstractPageAfterPublishPlugins = $productAbstractPageAfterPublishPlugins;
    }

    /**
     * @param int[] $productAbstractIds
     *
     * @return void
     */
    public function publish(array $productAbstractIds)
    {
        parent::publish($productAbstractIds);

        foreach ($this->productAbstractPageAfterPublishPlugins as $productListProductAbstractAfterPublishPlugin) {
            $productListProductAbstractAfterPublishPlugin->execute();
        }
    }
}
