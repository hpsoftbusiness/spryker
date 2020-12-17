<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductListStorage\Business;

use Pyz\Zed\ProductListStorage\Business\ProductListProductAbstractStorage\ProductListProductAbstractStorageWriter;
use Pyz\Zed\ProductListStorage\ProductListStorageDependencyProvider;
use Spryker\Zed\ProductListStorage\Business\ProductListProductAbstractStorage\ProductListProductAbstractStorageWriterInterface;
use Spryker\Zed\ProductListStorage\Business\ProductListStorageBusinessFactory as SprykerProductListStorageBusinessFactory;

class ProductListStorageBusinessFactory extends SprykerProductListStorageBusinessFactory
{
    /**
     * @return \Spryker\Zed\ProductListStorage\Business\ProductListProductAbstractStorage\ProductListProductAbstractStorageWriterInterface
     */
    public function createProductListProductAbstractStorageWriter(): ProductListProductAbstractStorageWriterInterface
    {
        return new ProductListProductAbstractStorageWriter(
            $this->getProductListFacade(),
            $this->getRepository(),
            $this->getConfig(),
            $this->getProductListProductAbstractAfterPublishPlugins()
        );
    }

    /**
     * @return \Pyz\Zed\ProductListStorage\Dependency\Plugin\ProductListProductAbstractAfterPublishPluginInterface[]
     */
    public function getProductListProductAbstractAfterPublishPlugins(): array
    {
        return $this->getProvidedDependency(ProductListStorageDependencyProvider::PLUGIN_PRODUCT_LIST_PRODUCT_ABSTRACT_AFTER_PUBLISH);
    }
}
