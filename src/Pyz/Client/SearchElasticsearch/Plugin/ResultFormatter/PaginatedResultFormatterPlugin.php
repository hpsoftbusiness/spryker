<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
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
