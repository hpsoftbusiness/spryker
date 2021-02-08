<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Product\Business;

use Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery;
use Pyz\Zed\Product\Business\Expander\OrderItemExpander;
use Pyz\Zed\Product\Business\Expander\OrderItemExpanderInterface;
use Pyz\Zed\Product\Business\Product\CheckerOrderItem;
use Pyz\Zed\Product\Business\Product\ProductConcreteRemover;
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

    public function createCheckedOrderItem(): CheckerOrderItem
    {
        return new CheckerOrderItem(new SpySalesOrderItemQuery());
    }

    /**
     * @return ProductConcreteRemover
     */
    public function createProductRemover(): ProductConcreteRemover
    {
        return new ProductConcreteRemover(
            $this->createProductConcreteActivator(),
            $this->createProductAbstractManager(),
            $this->createProductConcreteManager(),
            $this->getQueryContainer(),
            $this->createCheckedOrderItem()
        );
    }
}
