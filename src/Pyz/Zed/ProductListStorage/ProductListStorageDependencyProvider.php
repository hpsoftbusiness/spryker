<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductListStorage;

use Pyz\Zed\NavigationStorage\Communication\Plugin\ProductListStorage\CategoryNavigationPublishTriggerPlugin;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ProductListStorage\ProductListStorageDependencyProvider as SprykerProductListStorageDependencyProvider;

class ProductListStorageDependencyProvider extends SprykerProductListStorageDependencyProvider
{
    public const PLUGIN_PRODUCT_LIST_PRODUCT_ABSTRACT_AFTER_PUBLISH = 'PLUGIN_PRODUCT_LIST_PRODUCT_ABSTRACT_AFTER_PUBLISH';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);
        $container = $this->addProductListProductAbstractAfterPublishPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductListProductAbstractAfterPublishPlugins(Container $container): Container
    {
        $container->set(static::PLUGIN_PRODUCT_LIST_PRODUCT_ABSTRACT_AFTER_PUBLISH, [
            new CategoryNavigationPublishTriggerPlugin(),
        ]);

        return $container;
    }
}
