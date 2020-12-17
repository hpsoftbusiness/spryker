<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\Catalog\Plugin\Elasticsearch\Query;

use Elastica\Query;
use Elastica\Query\AbstractQuery;
use Elastica\Query\BoolQuery;
use Elastica\Query\MatchAll;
use Generated\Shared\Search\PageIndexMap;
use Generated\Shared\Transfer\SearchContextTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\Search\Dependency\Plugin\QueryInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\SearchContextAwareQueryInterface;

class CatalogVisibilitySearchQueryPlugin extends AbstractPlugin implements QueryInterface, SearchContextAwareQueryInterface
{
    protected const SOURCE_IDENTIFIER = 'page';

    /**
     * @var string
     */
    protected $searchString;

    /**
     * @var \Elastica\Query
     */
    protected $query;

    /**
     * @var \Generated\Shared\Transfer\SearchContextTransfer
     */
    protected $searchContextTransfer;

    public function __construct()
    {
        $this->query = $this->createSearchQuery();
    }

    /**
     * {@inheritDoc}
     * - Returns query object for catalog search.
     *
     * @api
     *
     * @return \Elastica\Query
     */
    public function getSearchQuery()
    {
        return $this->query;
    }

    /**
     * {@inheritDoc}
     * - Defines a context for catalog search.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\SearchContextTransfer
     */
    public function getSearchContext(): SearchContextTransfer
    {
        if (!$this->hasSearchContext()) {
            $this->setupDefaultSearchContext();
        }

        return $this->searchContextTransfer;
    }

    /**
     * {@inheritDoc}
     * - Sets a context for catalog search.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SearchContextTransfer $searchContextTransfer
     *
     * @return void
     */
    public function setSearchContext(SearchContextTransfer $searchContextTransfer): void
    {
        $this->searchContextTransfer = $searchContextTransfer;
    }

    /**
     * @return \Elastica\Query
     */
    protected function createSearchQuery()
    {
        $query = new Query();
        $query = $this->addSearchToQuery($query);
        $query->setSource([PageIndexMap::SEARCH_RESULT_DATA]);

        return $query;
    }

    /**
     * @param \Elastica\Query $baseQuery
     *
     * @return \Elastica\Query
     */
    protected function addSearchToQuery(Query $baseQuery)
    {
        $matchQuery = new MatchAll();
        $baseQuery->setQuery($this->createBoolQuery($matchQuery));

        return $baseQuery;
    }

    /**
     * @param \Elastica\Query\AbstractQuery $matchQuery
     *
     * @return \Elastica\Query\BoolQuery
     */
    protected function createBoolQuery(AbstractQuery $matchQuery)
    {
        $boolQuery = new BoolQuery();
        $boolQuery->addMust($matchQuery);

        return $boolQuery;
    }

    /**
     * @return void
     */
    protected function setupDefaultSearchContext(): void
    {
        $searchContextTransfer = new SearchContextTransfer();
        $searchContextTransfer->setSourceIdentifier(static::SOURCE_IDENTIFIER);

        $this->searchContextTransfer = $searchContextTransfer;
    }

    /**
     * @return bool
     */
    protected function hasSearchContext(): bool
    {
        return (bool)$this->searchContextTransfer;
    }
}
