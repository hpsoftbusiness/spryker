<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\Catalog;

use Spryker\Client\Catalog\CatalogFactory as SprykerCatalogFactory;
use Spryker\Client\Search\Dependency\Plugin\QueryInterface;

class CatalogFactory extends SprykerCatalogFactory
{
    /**
     * @return \Spryker\Client\Search\Dependency\Plugin\QueryInterface
     */
    public function getCatalogVisibilitySearchQueryPlugin(): QueryInterface
    {
        return $this->getProvidedDependency(CatalogDependencyProvider::CATALOG_VISIBILITY_SEARCH_QUERY_PLUGIN);
    }

    /**
     * @return \Spryker\Client\Search\Dependency\Plugin\QueryExpanderPluginInterface[]
     */
    public function getCatalogVisibilitySearchQueryExpanderPlugins(): array
    {
        return $this->getProvidedDependency(CatalogDependencyProvider::CATALOG_VISIBILITY_SEARCH_QUERY_EXPANDER_PLUGINS);
    }

    /**
     * @return \Spryker\Client\Search\Dependency\Plugin\ResultFormatterPluginInterface[]
     */
    public function getCatalogVisibilitySearchResultFormatters(): array
    {
        return $this->getProvidedDependency(CatalogDependencyProvider::CATALOG_VISIBILITY_SEARCH_RESULT_FORMATTER_PLUGINS);
    }
}
