<?php
declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\ProductFeed;

use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Pyz\Client\ProductFeed\ProductFeedFactory getFactory()
 */
class ProductFeedClient extends AbstractClient implements ProductFeedClientInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $searchString
     * @param array $requestParameters
     *
     * @return array
     */
    public function catalogSearch($searchString, array $requestParameters = [])
    {
        $searchQuery = $this
            ->getFactory()
            ->createCatalogSearchQuery($searchString);

        $searchQuery
            ->getSearchQuery()
            ->setSort([
                'search-result-data.id_product_abstract' => [
                    'order' => 'desc',
                    'mode' => 'min',
                ],
            ]);

        $searchQuery = $this
            ->getFactory()
            ->getSearchClient()
            ->expandQuery($searchQuery, $this->getFactory()->getCatalogSearchQueryExpanderPlugins(), $requestParameters);

        $resultFormatters = $this
            ->getFactory()
            ->getCatalogSearchResultFormatters();

        return $this
            ->getFactory()
            ->getSearchClient()
            ->search($searchQuery, $resultFormatters, $requestParameters);
    }
}
