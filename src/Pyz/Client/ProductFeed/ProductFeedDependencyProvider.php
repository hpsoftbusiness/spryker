<?php
declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\ProductFeed;

use Pyz\Client\SearchElasticsearch\Plugin\QueryExpander\BenefitStoreQueryExpanderPlugin;
use Pyz\Client\SearchElasticsearch\Plugin\QueryExpander\BenefitVoucherDealsQueryExpanderPlugin;
use Pyz\Client\SearchElasticsearch\Plugin\QueryExpander\EliteClubDealsQueryExpanderPlugin;
use Pyz\Client\SearchElasticsearch\Plugin\QueryExpander\FacetQueryExpanderPlugin;
use Pyz\Client\SearchElasticsearch\Plugin\QueryExpander\FeaturedProductQueryExpanderPlugin;
use Pyz\Client\SearchElasticsearch\Plugin\QueryExpander\IsAffiliateQueryExpanderPlugin;
use Pyz\Client\SearchElasticsearch\Plugin\QueryExpander\OnlyEliteClubDealsQueryExpanderPlugin;
use Pyz\Client\SearchElasticsearch\Plugin\QueryExpander\ShoppingPointDealsQueryExpanderPlugin;
use Pyz\Client\SearchElasticsearch\Plugin\QueryExpander\ShoppingPointStoreQueryExpanderPlugin;
use Pyz\Client\SearchElasticsearch\Plugin\ResultFormatter\PaginatedResultFormatterPlugin;
use Spryker\Client\Catalog\Plugin\Elasticsearch\Query\ProductCatalogSearchQueryPlugin;
use Spryker\Client\Catalog\Plugin\Elasticsearch\ResultFormatter\RawCatalogSearchResultFormatterPlugin;
use Spryker\Client\CatalogPriceProductConnector\Plugin\CurrencyAwareCatalogSearchResultFormatterPlugin;
use Spryker\Client\CatalogPriceProductConnector\Plugin\ProductPriceQueryExpanderPlugin;
use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;
use Spryker\Client\SearchElasticsearch\Plugin\QueryExpander\IsActiveInDateRangeQueryExpanderPlugin;
use Spryker\Client\SearchElasticsearch\Plugin\QueryExpander\IsActiveQueryExpanderPlugin;
use Spryker\Client\SearchElasticsearch\Plugin\QueryExpander\LocalizedQueryExpanderPlugin;
use Spryker\Client\SearchElasticsearch\Plugin\QueryExpander\PaginatedQueryExpanderPlugin;
use Spryker\Client\SearchElasticsearch\Plugin\QueryExpander\StoreQueryExpanderPlugin;
use Spryker\Client\SearchElasticsearch\Plugin\ResultFormatter\FacetResultFormatterPlugin;
use Spryker\Client\SearchElasticsearch\Plugin\ResultFormatter\SortedResultFormatterPlugin;

class ProductFeedDependencyProvider extends AbstractDependencyProvider
{
    public const CATALOG_SEARCH_RESULT_FORMATTER_PLUGINS = 'CATALOG_SEARCH_RESULT_FORMATTER_PLUGINS';
    public const CATALOG_SEARCH_QUERY_EXPANDER_PLUGINS = 'CATALOG_SEARCH_QUERY_EXPANDER_PLUGINS';
    public const CATALOG_SEARCH_QUERY_PLUGIN = 'CATALOG_SEARCH_QUERY_PLUGIN';
    public const CLIENT_SEARCH = 'CLIENT_SEARCH';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container)
    {
        $container = parent::provideServiceLayerDependencies($container);
        $container = $this->addCatalogSerachResultFormatterPlugins($container);
        $container = $this->addCatalogSearchQueryExpanderPlugins($container);
        $container = $this->addCatalogSearchQueryPlugin($container);
        $container = $this->addSearchClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addCatalogSerachResultFormatterPlugins(Container $container)
    {
        $container->set(static::CATALOG_SEARCH_RESULT_FORMATTER_PLUGINS, function () {
            return $this->createCatalogSearchResultFormatterPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addCatalogSearchQueryExpanderPlugins(Container $container)
    {
        $container->set(static::CATALOG_SEARCH_QUERY_EXPANDER_PLUGINS, function () {
            return $this->createCatalogSearchQueryExpanderPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addCatalogSearchQueryPlugin(Container $container)
    {
        $container->set(static::CATALOG_SEARCH_QUERY_PLUGIN, function () {
            return $this->createCatalogSearchQueryPlugin();
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addSearchClient(Container $container)
    {
        $container->set(static::CLIENT_SEARCH, function (Container $container) {
            return $container->getLocator()->search()->client();
        });

        return $container;
    }

    /**
     * @return \Spryker\Client\SearchExtension\Dependency\Plugin\ResultFormatterPluginInterface[]
     */
    protected function createCatalogSearchResultFormatterPlugins()
    {
        return [
            new FacetResultFormatterPlugin(),
            new SortedResultFormatterPlugin(),
            new PaginatedResultFormatterPlugin(),
            new CurrencyAwareCatalogSearchResultFormatterPlugin(
                new RawCatalogSearchResultFormatterPlugin()
            ),
        ];
    }

    /**
     * @return \Spryker\Client\SearchExtension\Dependency\Plugin\QueryExpanderPluginInterface[]
     */
    protected function createCatalogSearchQueryExpanderPlugins()
    {
        return [
            new StoreQueryExpanderPlugin(),
            new LocalizedQueryExpanderPlugin(),
            new ProductPriceQueryExpanderPlugin(),
            new PaginatedQueryExpanderPlugin(),
            new IsActiveQueryExpanderPlugin(),
            new IsActiveInDateRangeQueryExpanderPlugin(),

            /**
             * FacetQueryExpanderPlugin needs to be after other query expanders which filters down the results.
             */
            new FacetQueryExpanderPlugin(),
            new IsAffiliateQueryExpanderPlugin(),
            new BenefitStoreQueryExpanderPlugin(),
            new ShoppingPointStoreQueryExpanderPlugin(),
            new EliteClubDealsQueryExpanderPlugin(),
            new OnlyEliteClubDealsQueryExpanderPlugin(),
            new BenefitVoucherDealsQueryExpanderPlugin(),
            new ShoppingPointDealsQueryExpanderPlugin(),
            new FeaturedProductQueryExpanderPlugin(),
        ];
    }

    /**
     * @return \Spryker\Client\Catalog\Plugin\Elasticsearch\Query\ProductCatalogSearchQueryPlugin
     */
    protected function createCatalogSearchQueryPlugin(): ProductCatalogSearchQueryPlugin
    {
        return new ProductCatalogSearchQueryPlugin();
    }
}
