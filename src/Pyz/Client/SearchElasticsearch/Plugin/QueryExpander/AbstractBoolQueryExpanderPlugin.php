<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\SearchElasticsearch\Plugin\QueryExpander;

use Elastica\Query;
use Elastica\Query\BoolQuery;
use InvalidArgumentException;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryExpanderPluginInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface;

abstract class AbstractBoolQueryExpanderPlugin extends AbstractPlugin implements QueryExpanderPluginInterface
{
    protected const BOOL_PARAMETER_INDEXED_FIELD_NAME = '';

    /**
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface $searchQuery
     * @param array $requestParameters
     *
     * @return \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface
     */
    public function expandQuery(QueryInterface $searchQuery, array $requestParameters = []): QueryInterface
    {
        $boolParameterValue = $requestParameters[static::BOOL_PARAMETER_INDEXED_FIELD_NAME] ?? null;
        if ($boolParameterValue === null) {
            return $searchQuery;
        }

        $this->addBoolFieldParameterFilterToQuery($searchQuery->getSearchQuery(), (bool)$boolParameterValue);

        return $searchQuery;
    }

    /**
     * @param \Elastica\Query $query
     * @param bool $value
     *
     * @return void
     */
    protected function addBoolFieldParameterFilterToQuery(Query $query, bool $value): void
    {
        $boolQuery = $this->getBoolQuery($query);

        $matchQuery = $this->getFactory()
            ->createQueryBuilder()
            ->createMatchQuery()
            ->setField(static::BOOL_PARAMETER_INDEXED_FIELD_NAME, $value);

        $boolQuery->addMust($matchQuery);
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
                'Is Affiliate query expander available only with %s, got: %s',
                BoolQuery::class,
                get_class($boolQuery)
            ));
        }

        return $boolQuery;
    }
}
