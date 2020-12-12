<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Product\Business;

use Pyz\Zed\Product\Business\Expander\OrderItemExpander;
use Pyz\Zed\Product\Business\Expander\OrderItemExpanderInterface;
use Pyz\Zed\Product\Business\Product\ProductConcreteManager;
use Pyz\Zed\Product\Business\Transfer\ProductTransferMapper;
use Spryker\Zed\Product\Business\ProductBusinessFactory as SprykerProductBusinessFactory;

/**
 * @method \Spryker\Zed\Product\ProductConfig getConfig()
 * @method \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Product\Persistence\ProductRepositoryInterface getRepository()
 */
class ProductBusinessFactory extends SprykerProductBusinessFactory
{
    /**
     * @return \Spryker\Zed\Product\Business\Transfer\ProductTransferMapperInterface
     */
    public function createProductTransferMapper()
    {
        return new ProductTransferMapper($this->createAttributeEncoder());
    }

    /**
     * @return \Spryker\Zed\Product\Business\Product\ProductConcreteManagerInterface
     */
    public function createProductConcreteManager()
    {
        $productConcreteManager = new ProductConcreteManager(
            $this->getQueryContainer(),
            $this->getTouchFacade(),
            $this->getLocaleFacade(),
            $this->createProductAbstractAssertion(),
            $this->createProductConcreteAssertion(),
            $this->createAttributeEncoder(),
            $this->createProductTransferMapper(),
            $this->getRepository()
        );

        $productConcreteManager->setEventFacade($this->getEventFacade());
        $productConcreteManager->attachBeforeCreateObserver($this->createProductConcreteBeforeCreateObserverPluginManager());
        $productConcreteManager->attachAfterCreateObserver($this->createProductConcreteAfterCreateObserverPluginManager());
        $productConcreteManager->attachBeforeUpdateObserver($this->createProductConcreteBeforeUpdateObserverPluginManager());
        $productConcreteManager->attachAfterUpdateObserver($this->createProductConcreteAfterUpdateObserverPluginManager());
        $productConcreteManager->attachReadObserver($this->createProductConcreteReadObserverPluginManager());

        return $productConcreteManager;
    }

    /**
     * @return \Pyz\Zed\Product\Business\Expander\OrderItemExpanderInterface
     */
    public function createOrderItemExpander(): OrderItemExpanderInterface
    {
        return new OrderItemExpander(
            $this->getRepository()
        );
    }
}
