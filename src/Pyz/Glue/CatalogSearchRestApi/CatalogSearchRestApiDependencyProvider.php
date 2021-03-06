<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Glue\CatalogSearchRestApi;

use Pyz\Glue\CatalogSearchRestApi\Plugin\ProductAbstractExpander\ProductAbstractAttributesExpanderPlugin;
use Pyz\Glue\CatalogSearchRestApi\Plugin\ProductAbstractExpander\ProductAbstractBenefitDealsExpanderPlugin;
use Pyz\Glue\CatalogSearchRestApi\Plugin\ProductAbstractExpander\ProductAbstractBenefitExpanderPlugin;
use Pyz\Glue\CatalogSearchRestApi\Plugin\ProductAbstractExpander\ProductAbstractCustomerGroupExpanderPlugin;
use Pyz\Glue\CatalogSearchRestApi\Plugin\ProductAbstractExpander\ProductAbstractEliteClubExpanderPlugin;
use Pyz\Glue\CatalogSearchRestApi\Plugin\ProductAbstractExpander\ProductAbstractFullUrlExpanderPlugin;
use Pyz\Glue\CatalogSearchRestApi\Plugin\ProductAbstractExpander\ProductAbstractSellableExpanderPlugin;
use Pyz\Glue\CatalogSearchRestApi\Plugin\ProductAbstractExpander\ProductAbstractShoppingPointDealsExpanderPlugin;
use Pyz\Glue\CatalogSearchRestApi\Plugin\ProductAbstractExpander\ProductAbstractShoppingPointsCashbackAmountExpanderPlugin;
use Spryker\Glue\CatalogSearchRestApi\CatalogSearchRestApiDependencyProvider as SprykerCatalogSearchRestApiDependencyProvider;
use Spryker\Glue\Kernel\Container;

class CatalogSearchRestApiDependencyProvider extends SprykerCatalogSearchRestApiDependencyProvider
{
    public const PLUGINS_PRODUCT_ABSTRACT_EXPANDER = 'PLUGINS_PRODUCT_ABSTRACT_EXPANDER';

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);

        $this->addProductAbstractExpanderPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return void
     */
    private function addProductAbstractExpanderPlugins(Container $container): void
    {
        $container->set(
            self::PLUGINS_PRODUCT_ABSTRACT_EXPANDER,
            static function () {
                return [
                    new ProductAbstractFullUrlExpanderPlugin(),
                    new ProductAbstractAttributesExpanderPlugin(),
                    new ProductAbstractBenefitExpanderPlugin(),
                    new ProductAbstractSellableExpanderPlugin(),
                    new ProductAbstractEliteClubExpanderPlugin(),
                    new ProductAbstractBenefitDealsExpanderPlugin(),
                    new ProductAbstractShoppingPointDealsExpanderPlugin(),
                    new ProductAbstractCustomerGroupExpanderPlugin(),
                    new ProductAbstractShoppingPointsCashbackAmountExpanderPlugin(),
                ];
            }
        );
    }
}
