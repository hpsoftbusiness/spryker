<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\BenefitDeal;

use Pyz\Zed\BenefitDeal\Communication\Plugin\ItemEntityExpander\ShoppingPointsItemEntityExpanderPlugin;
use Pyz\Zed\BenefitDeal\Communication\Plugin\ItemHydrator\ShoppingPointsItemHydratorPlugin;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class BenefitDealDependencyProvider extends AbstractBundleDependencyProvider
{
    public const PLUGINS_ITEM_ENTITY_EXPANDER = 'PLUGINS_ITEM_ENTITY_EXPANDER';
    public const PLUGINS_ITEM_BENEFIT_DEAL_HYDRATOR = 'PLUGINS_ITEM_BENEFIT_DEAL_HYDRATOR';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);

        $this->addItemEntityExpanderPlugins($container);
        $this->addItemBenefitDealHydratorPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    private function addItemEntityExpanderPlugins(Container $container): void
    {
        $container->set(
            self::PLUGINS_ITEM_ENTITY_EXPANDER,
            static function () {
                return [
                    new ShoppingPointsItemEntityExpanderPlugin(),
                ];
            }
        );
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    private function addItemBenefitDealHydratorPlugins(Container $container): void
    {
        $container->set(
            self::PLUGINS_ITEM_BENEFIT_DEAL_HYDRATOR,
            static function () {
                return [
                    new ShoppingPointsItemHydratorPlugin(),
                ];
            }
        );
    }
}
