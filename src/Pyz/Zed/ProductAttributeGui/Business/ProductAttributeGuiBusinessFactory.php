<?php

namespace Pyz\Zed\ProductAttributeGui\Business;

use Pyz\Zed\ProductAttributeGui\Business\Modal\Reader\ProductReader;
use Pyz\Zed\ProductAttributeGui\Business\Modal\Reader\ProductReaderInterface;
use Pyz\Zed\ProductAttributeGui\Business\Modal\Writer\ProductAttributeWriter;
use Pyz\Zed\ProductAttributeGui\Business\Modal\Writer\ProductAttributeWriterInterface;
use Pyz\Zed\ProductAttributeGui\Dependency\QueryContainer\ProductAttributeGuiToProductAttributeQueryContainerInterface;
use Pyz\Zed\ProductAttributeGui\ProductAttributeGuiDependencyProvider;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductAttributeGui\Dependency\QueryContainer\ProductAttributeGuiToProductInterface;

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
     * @return \Pyz\Zed\ProductAttributeGui\Business\Modal\Writer\ProductAttributeWriter
     */
    public function createProductAttributeWriter(): ProductAttributeWriterInterface
    {
        return new ProductAttributeWriter($this->getProductAttributeQueryContainer());
    }
}
