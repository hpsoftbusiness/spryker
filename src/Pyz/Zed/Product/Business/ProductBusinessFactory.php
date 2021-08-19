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
use Pyz\Zed\Product\Business\Product\Url\ProductUrlGenerator;
use Pyz\Zed\Product\Business\Product\Variant\VariantGenerator;
use Pyz\Zed\Product\ProductDependencyProvider;
use Pyz\Zed\ProductAttribute\Business\ProductAttributeFacadeInterface;
use Spryker\Zed\Product\Business\Product\Variant\VariantGeneratorInterface;
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
     * @return \Pyz\Zed\Product\Business\Product\CheckerOrderItem
     */
    public function createCheckedOrderItem(): CheckerOrderItem
    {
        return new CheckerOrderItem($this->createSpySalesOrderItemQuery());
    }

    /**
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery
     */
    public function createSpySalesOrderItemQuery(): SpySalesOrderItemQuery
    {
        return new SpySalesOrderItemQuery();
    }

    /**
     * @return \Pyz\Zed\Product\Business\Product\ProductConcreteRemover
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

    /**
     * @return \Spryker\Zed\Product\Business\Product\Variant\VariantGeneratorInterface
     */
    public function createProductVariantGenerator(): VariantGeneratorInterface
    {
        return new VariantGenerator(
            $this->getUrlFacade(),
            $this->createSkuGenerator(),
            $this->getProductAttributeFacade()
        );
    }

    /**
     * @return \Pyz\Zed\ProductAttribute\Business\ProductAttributeFacadeInterface
     */
    public function getProductAttributeFacade(): ProductAttributeFacadeInterface
    {
        return $this->getProvidedDependency(ProductDependencyProvider::FACADE_PRODUCT_ATTRIBUTE);
    }

    /**
     * @return \Spryker\Zed\Product\Business\Product\Url\ProductUrlGeneratorInterface
     */
    public function createProductUrlGenerator()
    {
        return new ProductUrlGenerator(
            $this->createProductAbstractNameGenerator(),
            $this->getLocaleFacade(),
            $this->getUtilTextService()
        );
    }
}
