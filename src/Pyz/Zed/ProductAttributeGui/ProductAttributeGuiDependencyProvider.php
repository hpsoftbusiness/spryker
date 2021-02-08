<?php

namespace Pyz\Zed\ProductAttributeGui;

use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ProductAttributeGui\ProductAttributeGuiDependencyProvider as SpyProductAttributeGuiDependencyProvider;

class ProductAttributeGuiDependencyProvider extends SpyProductAttributeGuiDependencyProvider
{
    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = $this->addProductAttributeQueryContainer($container);

        return $container;
    }
}
