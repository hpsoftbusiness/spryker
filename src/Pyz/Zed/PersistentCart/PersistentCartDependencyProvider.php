<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\PersistentCart;

use Pyz\Zed\BenefitDeal\Communication\Plugin\PersistentCart\BenefitDealsPersistentQuoteEqualizerPlugin;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\PersistentCart\PersistentCartDependencyProvider as SprykerPersistentCartDependencyProvider;
use Spryker\Zed\PersistentCartExtension\Dependency\Plugin\QuoteItemFinderPluginInterface;
use Spryker\Zed\ProductBundle\Communication\Plugin\PersistentCart\BundleProductQuoteItemFinderPlugin;
use Spryker\Zed\ProductBundle\Communication\Plugin\PersistentCart\RemoveBundleChangeRequestExpanderPlugin;

class PersistentCartDependencyProvider extends SprykerPersistentCartDependencyProvider
{
    public const PLUGINS_PERSISTENT_QUOTE_EQUALIZER = 'PLUGINS_PERSISTENT_QUOTE_EQUALIZER';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);

        $this->addPersistentQuoteEqualizerPlugins($container);

        return $container;
    }

    /**
     * @return \Spryker\Zed\PersistentCartExtension\Dependency\Plugin\QuoteItemFinderPluginInterface
     */
    protected function getQuoteItemFinderPlugin(): QuoteItemFinderPluginInterface
    {
        return new BundleProductQuoteItemFinderPlugin(); #ProductBundleFeature
    }

    /**
     * @return \Spryker\Zed\PersistentCartExtension\Dependency\Plugin\CartChangeRequestExpandPluginInterface[]
     */
    protected function getRemoveItemsRequestExpanderPlugins(): array
    {
        return [
            new RemoveBundleChangeRequestExpanderPlugin(), #ProductBundleFeature
        ];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    private function addPersistentQuoteEqualizerPlugins(Container $container): void
    {
        $container->set(
            self::PLUGINS_PERSISTENT_QUOTE_EQUALIZER,
            static function () {
                return [
                    new BenefitDealsPersistentQuoteEqualizerPlugin(),
                ];
            }
        );
    }
}
