<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Product;

use Pyz\Zed\PriceProduct\Communication\Plugin\Product\BenefitPriceDataHealerPlugin;
use Pyz\Zed\Product\Communication\Plugin\Product\DataHealer\SuperAttributeLocalizedValuesHealerPlugin;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\PriceProduct\Communication\Plugin\ProductAbstract\PriceProductAbstractAfterCreatePlugin;
use Spryker\Zed\PriceProduct\Communication\Plugin\ProductAbstract\PriceProductAbstractAfterUpdatePlugin;
use Spryker\Zed\PriceProduct\Communication\Plugin\ProductAbstract\PriceProductAbstractReadPlugin;
use Spryker\Zed\PriceProduct\Communication\Plugin\ProductConcrete\ConcreteProductPriceProductConcreteReadPlugin;
use Spryker\Zed\PriceProduct\Communication\Plugin\ProductConcrete\PriceProductConcreteAfterCreatePlugin;
use Spryker\Zed\PriceProduct\Communication\Plugin\ProductConcrete\PriceProductConcreteAfterUpdatePlugin;
use Spryker\Zed\Product\ProductDependencyProvider as SprykerProductDependencyProvider;
use Spryker\Zed\ProductAlternativeGui\Communication\Plugin\Product\ProductConcretePluginUpdate as ProductAlternativeGuiProductConcretePluginUpdate;
use Spryker\Zed\ProductBundle\Communication\Plugin\Product\ProductBundleDeactivatorProductConcreteAfterUpdatePlugin;
use Spryker\Zed\ProductBundle\Communication\Plugin\Product\ProductBundleProductConcreteAfterCreatePlugin;
use Spryker\Zed\ProductBundle\Communication\Plugin\Product\ProductBundleProductConcreteAfterUpdatePlugin;
use Spryker\Zed\ProductBundle\Communication\Plugin\Product\ProductBundleProductConcreteReadPlugin;
use Spryker\Zed\ProductDiscontinued\Communication\Plugin\SaveDiscontinuedNotesProductConcretePluginUpdate;
use Spryker\Zed\ProductDiscontinuedProductBundleConnector\Communication\Plugin\Product\DiscontinuedProductConcreteAfterCreatePlugin;
use Spryker\Zed\ProductDiscontinuedProductBundleConnector\Communication\Plugin\Product\DiscontinuedProductConcreteAfterUpdatePlugin;
use Spryker\Zed\ProductImage\Communication\Plugin\ProductAbstractAfterCreatePlugin as ImageSetProductAbstractAfterCreatePlugin;
use Spryker\Zed\ProductImage\Communication\Plugin\ProductAbstractAfterUpdatePlugin as ImageSetProductAbstractAfterUpdatePlugin;
use Spryker\Zed\ProductImage\Communication\Plugin\ProductAbstractReadPlugin as ImageSetProductAbstractReadPlugin;
use Spryker\Zed\ProductImage\Communication\Plugin\ProductConcreteAfterCreatePlugin as ImageSetProductConcreteAfterCreatePlugin;
use Spryker\Zed\ProductImage\Communication\Plugin\ProductConcreteAfterUpdatePlugin as ImageSetProductConcreteAfterUpdatePlugin;
use Spryker\Zed\ProductImage\Communication\Plugin\ProductConcreteReadPlugin as ImageSetProductConcreteReadPlugin;
use Spryker\Zed\ProductSearch\Communication\Plugin\ProductConcrete\ProductSearchProductConcreteAfterCreatePlugin;
use Spryker\Zed\ProductSearch\Communication\Plugin\ProductConcrete\ProductSearchProductConcreteAfterUpdatePlugin;
use Spryker\Zed\ProductSearch\Communication\Plugin\ProductConcrete\ProductSearchProductConcreteReadPlugin;
use Spryker\Zed\ProductValidity\Communication\Plugin\ProductValidityCreatePlugin;
use Spryker\Zed\ProductValidity\Communication\Plugin\ProductValidityReadPlugin;
use Spryker\Zed\ProductValidity\Communication\Plugin\ProductValidityUpdatePlugin;
use Spryker\Zed\Stock\Communication\Plugin\ProductConcreteAfterCreatePlugin as StockProductConcreteAfterCreatePlugin;
use Spryker\Zed\Stock\Communication\Plugin\ProductConcreteAfterUpdatePlugin as StockProductConcreteAfterUpdatePlugin;
use Spryker\Zed\Stock\Communication\Plugin\ProductConcreteReadPlugin as StockProductConcreteReadPlugin;
use Spryker\Zed\TaxProductConnector\Communication\Plugin\TaxSetProductAbstractAfterCreatePlugin;
use Spryker\Zed\TaxProductConnector\Communication\Plugin\TaxSetProductAbstractAfterUpdatePlugin;
use Spryker\Zed\TaxProductConnector\Communication\Plugin\TaxSetProductAbstractReadPlugin;

class ProductDependencyProvider extends SprykerProductDependencyProvider
{
    public const FACADE_PRODUCT_ATTRIBUTE = 'FACADE_PRODUCT_ATTRIBUTE';
    public const PLUGINS_PRODUCT_DATA_HEALER = 'PLUGINS_PRODUCT_DATA_HEALER';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);

        $this->addProductAttributeFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = parent::provideCommunicationLayerDependencies($container);

        $this->addProductAttributeFacade($container);
        $this->addUtilEncodingService($container);
        $this->addEventFacade($container);
        $this->addProductDataHealerPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Product\Dependency\Plugin\ProductAbstractPluginCreateInterface[]
     */
    protected function getProductAbstractBeforeCreatePlugins(Container $container)
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Product\Dependency\Plugin\ProductAbstractPluginCreateInterface[]
     */
    protected function getProductAbstractAfterCreatePlugins(Container $container)
    {
        return [
            new ImageSetProductAbstractAfterCreatePlugin(),
            new TaxSetProductAbstractAfterCreatePlugin(),
            new PriceProductAbstractAfterCreatePlugin(),
        ];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Product\Dependency\Plugin\ProductAbstractPluginReadInterface[]
     */
    protected function getProductAbstractReadPlugins(Container $container)
    {
        return [
            new ImageSetProductAbstractReadPlugin(),
            new TaxSetProductAbstractReadPlugin(),
            new PriceProductAbstractReadPlugin(),
        ];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Product\Dependency\Plugin\ProductAbstractPluginUpdateInterface[]
     */
    protected function getProductAbstractBeforeUpdatePlugins(Container $container)
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Product\Dependency\Plugin\ProductAbstractPluginUpdateInterface[]
     */
    protected function getProductAbstractAfterUpdatePlugins(Container $container)
    {
        return [
            new ImageSetProductAbstractAfterUpdatePlugin(),
            new TaxSetProductAbstractAfterUpdatePlugin(),
            new PriceProductAbstractAfterUpdatePlugin(),
        ];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Product\Dependency\Plugin\ProductConcretePluginCreateInterface[]
     */
    protected function getProductConcreteAfterCreatePlugins(Container $container)
    {
        return [
            new ImageSetProductConcreteAfterCreatePlugin(),
            new StockProductConcreteAfterCreatePlugin(),
            new PriceProductConcreteAfterCreatePlugin(),
            new ProductSearchProductConcreteAfterCreatePlugin(),
            new ProductBundleProductConcreteAfterCreatePlugin(),
            new ProductValidityCreatePlugin(),
            new DiscontinuedProductConcreteAfterCreatePlugin(),
        ];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Product\Dependency\Plugin\ProductConcretePluginReadInterface[]
     */
    protected function getProductConcreteReadPlugins(Container $container)
    {
        return [
            new ImageSetProductConcreteReadPlugin(),
            new StockProductConcreteReadPlugin(),
            new ConcreteProductPriceProductConcreteReadPlugin(),
            new ProductSearchProductConcreteReadPlugin(),
            new ProductBundleProductConcreteReadPlugin(),
            new ProductValidityReadPlugin(),
        ];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Product\Dependency\Plugin\ProductConcretePluginUpdateInterface[]
     */
    protected function getProductConcreteBeforeUpdatePlugins(Container $container)
    {
        return [
            new ProductAlternativeGuiProductConcretePluginUpdate(), #ProductAlternativeFeature
        ];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Product\Dependency\Plugin\ProductConcretePluginUpdateInterface[]
     */
    protected function getProductConcreteAfterUpdatePlugins(Container $container)
    {
        return [
            new ImageSetProductConcreteAfterUpdatePlugin(),
            new StockProductConcreteAfterUpdatePlugin(),
            new PriceProductConcreteAfterUpdatePlugin(),
            new ProductSearchProductConcreteAfterUpdatePlugin(),
            new ProductBundleProductConcreteAfterUpdatePlugin(),
            new ProductValidityUpdatePlugin(),
            new SaveDiscontinuedNotesProductConcretePluginUpdate(),
            new DiscontinuedProductConcreteAfterUpdatePlugin(),
            new ProductBundleDeactivatorProductConcreteAfterUpdatePlugin(),
        ];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addEventFacade(Container $container): Container
    {
        $container->set(
            self::FACADE_EVENT,
            static function (Container $container) {
                return $container->getLocator()->event()->facade();
            }
        );

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    private function addProductAttributeFacade(Container $container): void
    {
        $container->set(
            self::FACADE_PRODUCT_ATTRIBUTE,
            static function (Container $container) {
                return $container->getLocator()->productAttribute()->facade();
            }
        );
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    private function addProductDataHealerPlugins(Container $container): void
    {
        $container->set(
            self::PLUGINS_PRODUCT_DATA_HEALER,
            function () {
                return $this->getProductDataHealerPlugins();
            }
        );
    }

    /**
     * @return \Pyz\Zed\Product\Dependency\Plugin\ProductDataHealerPluginInterface[]
     */
    private function getProductDataHealerPlugins(): array
    {
        return [
            new SuperAttributeLocalizedValuesHealerPlugin(),
            new BenefitPriceDataHealerPlugin(),
        ];
    }
}
