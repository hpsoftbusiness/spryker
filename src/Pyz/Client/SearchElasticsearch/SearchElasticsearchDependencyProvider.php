<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\SearchElasticsearch;

use Pyz\Client\MerchantProductOfferSearch\Plugin\MerchantNameSearchConfigExpanderPlugin;
use Spryker\Client\Catalog\Plugin\SearchElasticsearch\ElasticsearchCatalogSearchConfigBuilderPlugin;
use Spryker\Client\Kernel\Container;
use Spryker\Client\MerchantProductSearch\Plugin\Search\MerchantProductMerchantNameSearchConfigExpanderPlugin;
use Spryker\Client\ProductSearchConfigStorage\Plugin\Config\ProductSearchConfigExpanderPlugin;
use Spryker\Client\SearchElasticsearch\SearchElasticsearchDependencyProvider as SprykerSearchElasticsearchDependencyProvider;
use Spryker\Shared\Kernel\Store;

class SearchElasticsearchDependencyProvider extends SprykerSearchElasticsearchDependencyProvider
{
    public const STORE = 'STORE';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container): Container
    {
        $container = parent::provideServiceLayerDependencies($container);

        $this->addStore($container);

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addLocaleClient(Container $container): Container
    {
        $container->set(self::CLIENT_LOCALE, static function (Container $container) {
            return $container->getLocator()->locale()->client();
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\SearchExtension\Dependency\Plugin\SearchConfigBuilderPluginInterface[]
     */
    protected function getSearchConfigBuilderPlugins(Container $container): array
    {
        return [
            new ElasticsearchCatalogSearchConfigBuilderPlugin(),
        ];
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\SearchExtension\Dependency\Plugin\SearchConfigExpanderPluginInterface[]
     */
    protected function getSearchConfigExpanderPlugins(Container $container): array
    {
        return [
            new ProductSearchConfigExpanderPlugin(),
            new MerchantProductMerchantNameSearchConfigExpanderPlugin(),
            new MerchantNameSearchConfigExpanderPlugin(),
        ];
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return void
     */
    private function addStore(Container $container): void
    {
        $container->set(self::STORE, static function () {
            return Store::getInstance();
        });
    }
}
