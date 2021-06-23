<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Discount\Business;

use Pyz\Zed\Discount\Business\Calculator\Discount;
use Pyz\Zed\Discount\Business\Calculator\InternalDiscount;
use Pyz\Zed\Discount\Business\Calculator\InternalDiscountInterface;
use Pyz\Zed\Discount\Business\Collector\InternalDiscountCollector;
use Pyz\Zed\Discount\Business\Collector\InternalDiscountCollectorInterface;
use Pyz\Zed\Discount\Business\Collector\UseShoppingPointsCollector;
use Pyz\Zed\Discount\DiscountDependencyProvider;
use Spryker\Zed\Discount\Business\Calculator\DiscountInterface;
use Spryker\Zed\Discount\Business\Collector\CollectorInterface;
use Spryker\Zed\Discount\Business\DiscountBusinessFactory as SprykerDiscountBusinessFactory;

class DiscountBusinessFactory extends SprykerDiscountBusinessFactory
{
    /**
     * @return \Spryker\Zed\Discount\Business\Calculator\DiscountInterface
     */
    public function createDiscount(): DiscountInterface
    {
        $discount = new Discount(
            $this->getQueryContainer(),
            $this->createCalculator(),
            $this->createDecisionRuleBuilder(),
            $this->createVoucherValidator(),
            $this->createDiscountEntityMapper(),
            $this->getStoreFacade(),
            $this->createInternalDiscount()
        );

        $discount->setDiscountApplicableFilterPlugins($this->getDiscountApplicableFilterPlugins());

        return $discount;
    }

    /**
     * @return \Spryker\Zed\Discount\Business\Collector\CollectorInterface
     */
    public function createUseShoppingPointsCollector(): CollectorInterface
    {
        return new UseShoppingPointsCollector();
    }

    /**
     * @return \Pyz\Zed\Discount\Business\Calculator\InternalDiscountInterface
     */
    public function createInternalDiscount(): InternalDiscountInterface
    {
        return new InternalDiscount($this->getInternalDiscountPlugins());
    }

    /**
     * @return \Pyz\Zed\Discount\Business\Collector\InternalDiscountCollectorInterface
     */
    public function createInternalDiscountCollector(): InternalDiscountCollectorInterface
    {
        return new InternalDiscountCollector($this->getInternalDiscountCollectorPlugins());
    }

    /**
     * @return \Pyz\Zed\Discount\Dependency\Plugin\InternalDiscountCollectorPluginInterface[]
     */
    public function getInternalDiscountCollectorPlugins(): array
    {
        return $this->getProvidedDependency(DiscountDependencyProvider::PLUGIN_INTERNAL_DISCOUNT_COLLECTOR);
    }

    /**
     * @return \Pyz\Zed\Discount\Dependency\Plugin\InternalDiscountPluginInterface[]
     */
    public function getInternalDiscountPlugins(): array
    {
        return $this->getProvidedDependency(DiscountDependencyProvider::PLUGIN_INTERNAL_DISCOUNT);
    }
}
