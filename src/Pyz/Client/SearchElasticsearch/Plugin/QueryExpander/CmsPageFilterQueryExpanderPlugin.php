<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Pyz\Client\SearchElasticsearch\Plugin\QueryExpander;

use Elastica\Query;
use Elastica\Query\BoolQuery;
use Generated\Shared\Search\PageIndexMap;
use InvalidArgumentException;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryExpanderPluginInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface;

class CmsPageFilterQueryExpanderPlugin extends AbstractPlugin implements QueryExpanderPluginInterface
{
    protected const FILTER_TYPE = 'cms_page';

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface $searchQuery
     * @param array $requestParameters
     *
     * @return \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface
     */
    public function expandQuery(QueryInterface $searchQuery, array $requestParameters = []): QueryInterface
    {
        $this->addCmsPageFilterToQuery($searchQuery->getSearchQuery());

        return $searchQuery;
    }

    /**
     * @param \Elastica\Query $query
     *
     * @return void
     */
    protected function addCmsPageFilterToQuery(Query $query): void
    {
        $boolQuery = $this->getBoolQuery($query);

        $matchQuery = $this->getFactory()
            ->createQueryBuilder()
            ->createMatchQuery()
            ->setField(PageIndexMap::TYPE, static::FILTER_TYPE);

        $boolQuery->addMustNot($matchQuery);
    }

    /**
     * @param \Elastica\Query $query
     *
     * @throws \InvalidArgumentException
     *
     * @return \Elastica\Query\BoolQuery
     */
    protected function getBoolQuery(Query $query): BoolQuery
    {
        $boolQuery = $query->getQuery();
        if (!$boolQuery instanceof BoolQuery) {
            throw new InvalidArgumentException(sprintf(
                'Is Active query expander available only with %s, got: %s',
                BoolQuery::class,
                get_class($boolQuery)
            ));
        }

        return $boolQuery;
    }
}

