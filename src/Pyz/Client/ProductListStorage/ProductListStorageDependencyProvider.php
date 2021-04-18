<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\ProductListStorage;

use Spryker\Client\Kernel\Container;
use Spryker\Client\ProductListStorage\ProductListStorageDependencyProvider as SprykerProductListStorageDependencyProvider;

class ProductListStorageDependencyProvider extends SprykerProductListStorageDependencyProvider
{
    public const CLIENT_PRODUCT_LIST = 'CLIENT_PRODUCT_LIST';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container): Container
    {
        $container = parent::provideServiceLayerDependencies($container);
        $container = $this->addProductListClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addProductListClient(Container $container): Container
    {
        $container->set(static::CLIENT_PRODUCT_LIST, function (Container $container) {
            return $container->getLocator()->productList()->client();
        });

        return $container;
    }
}