<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Discount\Communication\Plugin\InternalDiscount;

use Pyz\Zed\Discount\Communication\Plugin\Collector\ShoppingPointsItemDiscountCollectorPlugin;
use Pyz\Zed\Discount\Dependency\Plugin\InternalDiscountPluginInterface;
use Pyz\Zed\Discount\DiscountDependencyProvider;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Pyz\Zed\Discount\Business\DiscountFacadeInterface getFacade()
 * @method \Pyz\Zed\Discount\DiscountConfig getConfig()
 * @method \Pyz\Zed\Discount\Communication\DiscountCommunicationFactory getFactory()
 */
class ShoppingPointsDiscountPlugin extends AbstractPlugin implements InternalDiscountPluginInterface
{
    private const DISPLAY_NAME = 'discount.internal.shopping_points';

    /**
     * @return string
     */
    public function getDisplayName(): string
    {
        return self::DISPLAY_NAME;
    }

    /**
     * @return string
     */
    public function getCalculatorPluginName(): string
    {
        return DiscountDependencyProvider::PLUGIN_CALCULATOR_BENEFIT_PRICE;
    }

    /**
     * @return string
     */
    public function getCollectorPluginName(): string
    {
        return ShoppingPointsItemDiscountCollectorPlugin::class;
    }
}
