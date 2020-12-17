<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductListStorage\Business\ProductListProductAbstractStorage;

use Spryker\Zed\ProductListStorage\Business\ProductListProductAbstractStorage\ProductListProductAbstractStorageWriter as SprykerProductListProductAbstractStorageWriter;
use Spryker\Zed\ProductListStorage\Dependency\Facade\ProductListStorageToProductListFacadeInterface;
use Spryker\Zed\ProductListStorage\Persistence\ProductListStorageRepositoryInterface;
use Spryker\Zed\ProductListStorage\ProductListStorageConfig;

class ProductListProductAbstractStorageWriter extends SprykerProductListProductAbstractStorageWriter
{
    /**
     * @var \Pyz\Zed\ProductListStorage\Dependency\Plugin\ProductListProductAbstractAfterPublishPluginInterface[]
     */
    protected $productListProductAbstractAfterPublishPlugins = [];

    /**
     * @param \Spryker\Zed\ProductListStorage\Dependency\Facade\ProductListStorageToProductListFacadeInterface $productListFacade
     * @param \Spryker\Zed\ProductListStorage\Persistence\ProductListStorageRepositoryInterface $productListStorageRepository
     * @param \Spryker\Zed\ProductListStorage\ProductListStorageConfig $productListStorageConfig
     * @param \Pyz\Zed\ProductListStorage\Dependency\Plugin\ProductListProductAbstractAfterPublishPluginInterface[] $productListProductAbstractAfterPublishPlugins
     */
    public function __construct(
        ProductListStorageToProductListFacadeInterface $productListFacade,
        ProductListStorageRepositoryInterface $productListStorageRepository,
        ProductListStorageConfig $productListStorageConfig,
        array $productListProductAbstractAfterPublishPlugins
    ) {
        parent::__construct($productListFacade, $productListStorageRepository, $productListStorageConfig);

        $this->productListProductAbstractAfterPublishPlugins = $productListProductAbstractAfterPublishPlugins;
    }

    /**
     * @param int[] $productAbstractIds
     *
     * @return void
     */
    public function publish(array $productAbstractIds): void
    {
        parent::publish($productAbstractIds);

        foreach ($this->productListProductAbstractAfterPublishPlugins as $productListProductAbstractAfterPublishPlugin) {
            $productListProductAbstractAfterPublishPlugin->execute();
        }
    }
}
