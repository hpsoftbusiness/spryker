<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\BenefitDeal;

use Pyz\Zed\BenefitDeal\Communication\Plugin\ItemEntityExpander\BenefitVoucherItemEntityExpanderPlugin;
use Pyz\Zed\BenefitDeal\Communication\Plugin\ItemEntityExpander\ShoppingPointsItemEntityExpanderPlugin;
use Pyz\Zed\BenefitDeal\Communication\Plugin\ItemHydrator\BenefitVoucherItemHydratorPlugin;
use Pyz\Zed\BenefitDeal\Communication\Plugin\ItemHydrator\ShoppingPointsItemHydratorPlugin;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class BenefitDealDependencyProvider extends AbstractBundleDependencyProvider
{
    public const PLUGINS_ITEM_ENTITY_EXPANDER = 'PLUGINS_ITEM_ENTITY_EXPANDER';
    public const PLUGINS_ITEM_BENEFIT_DEAL_HYDRATOR = 'PLUGINS_ITEM_BENEFIT_DEAL_HYDRATOR';

    public const FACADE_PRODUCT_LABEL = 'FACADE_PRODUCT_LABEL';
    public const FACADE_PRICE_PRODUCT = 'FACADE_PRICE_PRODUCT';

    public const CLIENT_STORE = 'CLIENT_STORE';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);
        $container = $this->addItemEntityExpanderPlugins($container);
        $container = $this->addItemBenefitDealHydratorPlugins($container);
        $container = $this->addProductLabelFacade($container);
        $container = $this->addPriceProductFacade($container);
        $container = $this->addStoreClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function providePersistenceLayerDependencies(Container $container): Container
    {
        $container = parent::providePersistenceLayerDependencies($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductLabelFacade(Container $container): Container
    {
        $container->set(static::FACADE_PRODUCT_LABEL, function (Container $container) {
            return $container->getLocator()->productLabel()->facade();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    private function addItemEntityExpanderPlugins(Container $container): Container
    {
        $container->set(
            self::PLUGINS_ITEM_ENTITY_EXPANDER,
            static function () {
                return [
                    new ShoppingPointsItemEntityExpanderPlugin(),
                    new BenefitVoucherItemEntityExpanderPlugin(),
                ];
            }
        );

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    private function addItemBenefitDealHydratorPlugins(Container $container): Container
    {
        $container->set(
            self::PLUGINS_ITEM_BENEFIT_DEAL_HYDRATOR,
            static function () {
                return [
                    new ShoppingPointsItemHydratorPlugin(),
                    new BenefitVoucherItemHydratorPlugin(),
                ];
            }
        );

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    private function addPriceProductFacade(Container $container): Container
    {
        $container->set(self::FACADE_PRICE_PRODUCT, static function (Container $container) {
            return $container->getLocator()->priceProduct()->facade();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    private function addStoreClient(Container $container): Container
    {
        $container->set(self::CLIENT_STORE, static function (Container $container) {
            return $container->getLocator()->store()->client();
        });

        return $container;
    }
}
