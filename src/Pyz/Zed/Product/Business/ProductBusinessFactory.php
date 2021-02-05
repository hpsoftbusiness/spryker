<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Product\Business;

use Pyz\Zed\Product\Business\Expander\OrderItemExpander;
use Pyz\Zed\Product\Business\Expander\OrderItemExpanderInterface;
use Pyz\Zed\Product\Business\Product\ProductConcreteActivator;
use Spryker\Zed\Product\Business\ProductBusinessFactory as SprykerProductBusinessFactory;

/**
 * @method \Spryker\Zed\Product\ProductConfig getConfig()
 * @method \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Product\Persistence\ProductRepositoryInterface getRepository()
 */
class ProductBusinessFactory extends SprykerProductBusinessFactory
{
    /**
     * @return \Pyz\Zed\Product\Business\Expander\OrderItemExpanderInterface
     */
    public function createOrderItemExpander(): OrderItemExpanderInterface
    {
        return new OrderItemExpander(
            $this->getRepository()
        );
    }

    /**
     * @return ProductConcreteActivator
     */
    public function createProductConcreteActivatorForRemove()
    {
        return new ProductConcreteActivator(
            $this->createProductAbstractStatusChecker(),
            $this->createProductAbstractManager(),
            $this->createProductConcreteManager(),
            $this->createProductUrlManager(),
            $this->createProductConcreteTouch(),
            $this->getQueryContainer(),
            $this->createAttributeEncoder()
        );
    }
}
