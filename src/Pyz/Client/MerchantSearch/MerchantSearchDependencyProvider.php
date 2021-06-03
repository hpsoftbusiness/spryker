<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\MerchantSearch;

use Spryker\Client\MerchantSearch\MerchantSearchDependencyProvider as SprykerMerchantSearchDependencyProvider;
use Spryker\Client\MerchantSearch\Plugin\Elasticsearch\Query\PaginatedMerchantSearchQueryExpanderPlugin;
use Spryker\Client\MerchantSearch\Plugin\Elasticsearch\ResultFormatter\MerchantSearchResultFormatterPlugin;
use Spryker\Client\SearchElasticsearch\Plugin\QueryExpander\StoreQueryExpanderPlugin;

class MerchantSearchDependencyProvider extends SprykerMerchantSearchDependencyProvider
{
    /**
     * @return array
     */
    protected function getMerchantSearchResultFormatterPlugins(): array
    {
        return [
            new MerchantSearchResultFormatterPlugin(),
            new PaginatedMerchantSearchQueryExpanderPlugin(),
            new StoreQueryExpanderPlugin(),
        ];
    }
}
