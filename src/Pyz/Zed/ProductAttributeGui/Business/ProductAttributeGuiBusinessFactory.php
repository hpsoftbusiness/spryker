<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

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
     * @return \Pyz\Zed\ProductAttributeGui\Dependency\QueryContainer\ProductAttributeGuiToProductAttributeQueryContainerInterface
     */
    public function getProductAttributeQueryContainer(): ProductAttributeGuiToProductAttributeQueryContainerInterface
    {
        return $this->getProvidedDependency(ProductAttributeGuiDependencyProvider::QUERY_CONTAINER_PRODUCT_ATTRIBUTE);
    }

    /**
     * @return \Pyz\Zed\Product\Persistence\ProductQueryContainerInterface
     */
    public function getProductQueryContainer(): ProductQueryContainerInterface
    {
        return $this->getProvidedDependency(ProductAttributeGuiDependencyProvider::QUERY_CONTAINER_PRODUCT);
    }
}
