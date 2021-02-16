<?php

namespace Pyz\Zed\ProductAttributeGui\Business;

use Pyz\Zed\Product\Persistence\ProductQueryContainerInterface;
use Pyz\Zed\ProductAttributeGui\Dependency\QueryContainer\ProductAttributeGuiToProductAttributeQueryContainerInterface;
use Pyz\Zed\ProductAttributeGui\ProductAttributeGuiDependencyProvider;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * Class AttributeBusinessFactory
 *
 * @package Pyz\Zed\ProductAttributeGui\Business
 */
class ProductAttributeGuiBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @throws \Spryker\Zed\Kernel\Exception\Container\ContainerKeyNotFoundException
     * @return \Pyz\Zed\ProductAttributeGui\Dependency\QueryContainer\ProductAttributeGuiToProductAttributeQueryContainerInterface
     */
    public function getProductAttributeQueryContainer(): ProductAttributeGuiToProductAttributeQueryContainerInterface
    {
        return $this->getProvidedDependency(ProductAttributeGuiDependencyProvider::QUERY_CONTAINER_PRODUCT_ATTRIBUTE);
    }

    /**
     * @throws \Spryker\Zed\Kernel\Exception\Container\ContainerKeyNotFoundException
     * @return \Pyz\Zed\Product\Persistence\ProductQueryContainerInterface
     */
    public function getProductQueryContainer(): ProductQueryContainerInterface
    {
        return $this->getProvidedDependency(ProductAttributeGuiDependencyProvider::QUERY_CONTAINER_PRODUCT);
    }
}
