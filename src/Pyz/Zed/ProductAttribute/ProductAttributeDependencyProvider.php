<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductAttribute;

use Pyz\Zed\ProductAttribute\Communication\Plugin\PreSaveCheck\ShoppingPointsAttributePreSaveCheckPlugin;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ProductAttribute\ProductAttributeDependencyProvider as SprykerProductAttributeDependencyProvider;

class ProductAttributeDependencyProvider extends SprykerProductAttributeDependencyProvider
{
    public const PLUGINS_PRODUCT_ATTRIBUTE_PRE_SAVE_CHECK = 'PLUGINS_PRODUCT_ATTRIBUTE_PRE_SAVE_CHECK';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);

        $this->addProductAttributePreSaveCheckPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    private function addProductAttributePreSaveCheckPlugins(Container $container): void
    {
        $container->set(
            self::PLUGINS_PRODUCT_ATTRIBUTE_PRE_SAVE_CHECK,
            static function () {
                return [
                    new ShoppingPointsAttributePreSaveCheckPlugin(),
                ];
            }
        );
    }
}
