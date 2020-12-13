<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\SearchElasticsearch\Plugin\ResultFormatter;

use Elastica\ResultSet;
use Generated\Shared\Transfer\PaginationSearchResultTransfer;
use Spryker\Client\SearchElasticsearch\Plugin\ResultFormatter\PaginatedResultFormatterPlugin as SprykerPaginatedResultFormatterPlugin;

class PaginatedResultFormatterPlugin extends SprykerPaginatedResultFormatterPlugin
{
    protected const NUMBER_RESULTS_MAX = 40;

    /**
     * @param \Elastica\ResultSet $searchResult
     * @param array $requestParameters
     *
     * @return \Generated\Shared\Transfer\PaginationSearchResultTransfer
     */
    protected function formatSearchResult(ResultSet $searchResult, array $requestParameters): PaginationSearchResultTransfer
    {
        $paginationSearchResultTransfer = parent::formatSearchResult($searchResult, $requestParameters);

        $paginationConfig = $this->getFactory()->getSearchConfig()->getPaginationConfig();
        $itemsPerPage = $paginationConfig->getCurrentItemsPerPage($requestParameters);
        $maxPage = min(
            (int)ceil($searchResult->getTotalHits() / $itemsPerPage),
            (int)ceil(static::NUMBER_RESULTS_MAX / $itemsPerPage)
        );

        return $paginationSearchResultTransfer
            ->setMaxPage($maxPage);
    }
}
