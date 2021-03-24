<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Service\ProductAffiliate;

use Pyz\Service\ProductAffiliate\Generator\Formatter\AwinTrackingLinkDataFormatterPlugin;
use Pyz\Service\ProductAffiliate\Generator\Formatter\ChineseanTrackingLinkDataFormatterPlugin;
use Pyz\Service\ProductAffiliate\Generator\Formatter\TradeDoublerTrackingLinkDataFormatterPlugin;
use Pyz\Service\ProductAffiliate\Generator\Formatter\TradeTrackerTrackingLinkDataFormatterPlugin;
use Pyz\Service\ProductAffiliate\Generator\Formatter\WebgainsTrackingLinkDataFormatterPlugin;
use Spryker\Service\Kernel\AbstractBundleDependencyProvider;
use Spryker\Service\Kernel\Container;

class ProductAffiliateDependencyProvider extends AbstractBundleDependencyProvider
{
    public const PLUGINS_TRACKING_LINK_DATA_FORMATTER = 'PLUGINS_TRACKING_LINK_DATA_FORMATTER';

    /**
     * @param \Spryker\Service\Kernel\Container $container
     *
     * @return \Spryker\Service\Kernel\Container
     */
    public function provideServiceDependencies(Container $container): Container
    {
        $container = parent::provideServiceDependencies($container);

        $this->addTrackingLinkDataFormatterPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Service\Kernel\Container $container
     *
     * @return void
     */
    private function addTrackingLinkDataFormatterPlugins(Container $container): void
    {
        $container->set(
            self::PLUGINS_TRACKING_LINK_DATA_FORMATTER,
            static function () {
                return [
                    new AwinTrackingLinkDataFormatterPlugin(),
                    new ChineseanTrackingLinkDataFormatterPlugin(),
                    new WebgainsTrackingLinkDataFormatterPlugin(),
                    new TradeDoublerTrackingLinkDataFormatterPlugin(),
                    new TradeTrackerTrackingLinkDataFormatterPlugin(),
                ];
            }
        );
    }
}
