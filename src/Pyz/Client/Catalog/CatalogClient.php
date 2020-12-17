<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\Catalog;

use Spryker\Client\Catalog\CatalogClient as SprykerCatalogClient;

/**
 * @method \Pyz\Client\Catalog\CatalogFactory getFactory()
 */
class CatalogClient extends SprykerCatalogClient implements CatalogClientInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param mixed[] $requestParameters
     *
     * @return mixed[]
     */
    public function getCatalogVisibility(array $requestParameters = []): array
    {
        $searchQuery = $this
            ->getFactory()
            ->getCatalogVisibilitySearchQueryPlugin();

        $searchQuery = $this
            ->getFactory()
            ->getSearchClient()
            ->expandQuery(
                $searchQuery,
                $this->getFactory()->getCatalogVisibilitySearchQueryExpanderPlugins(),
                $requestParameters
            );

        $resultFormatters = $this
            ->getFactory()
            ->getCatalogVisibilitySearchResultFormatters();

        return $this
            ->getFactory()
            ->getSearchClient()
            ->search($searchQuery, $resultFormatters, $requestParameters);
    }
}
