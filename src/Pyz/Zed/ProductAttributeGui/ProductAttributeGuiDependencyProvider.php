<?php

namespace Pyz\Zed\ProductAttributeGui;

use Pyz\Zed\ProductAttributeGui\Dependency\QueryContainer\ProductAttributeGuiToProductAttributeQueryContainerBridge;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ProductAttributeGui\ProductAttributeGuiDependencyProvider as SpyProductAttributeGuiDependencyProvider;

class ProductAttributeGuiDependencyProvider extends SpyProductAttributeGuiDependencyProvider
{
    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @throws \Spryker\Service\Container\Exception\FrozenServiceException
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = $this->addProductAttributeQueryContainer($container);
        $container = $this->addProductQueryContainer($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @throws \Spryker\Service\Container\Exception\FrozenServiceException
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductAttributeQueryContainer(Container $container): Container
    {
        $container->set(static::QUERY_CONTAINER_PRODUCT_ATTRIBUTE, function (Container $container) {
            return new ProductAttributeGuiToProductAttributeQueryContainerBridge(
                $container->getLocator()->productAttribute()->queryContainer()
            );
        });

        return $container;
    }
}
