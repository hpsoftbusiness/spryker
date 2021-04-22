<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\SearchElasticsearch\Plugin\QueryExpander;

use Elastica\Query;
use Elastica\Query\BoolQuery;
use Elastica\Query\Nested;
use Elastica\Query\Term;
use Generated\Shared\Search\PageIndexMap;
use InvalidArgumentException;
use Pyz\Shared\Catalog\CatalogConfig;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryExpanderPluginInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface;

/**
 * @method \Pyz\Client\SearchElasticsearch\SearchElasticsearchFactory getFactory()
 * @method \Pyz\Client\SearchElasticsearch\SearchElasticsearchConfig getConfig()
 */
class SellableQueryExpanderPlugin extends AbstractPlugin implements QueryExpanderPluginInterface
{
    /**
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface $searchQuery
     * @param array $requestParameters
     *
     * @return \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface
     */
    public function expandQuery(QueryInterface $searchQuery, array $requestParameters = []): QueryInterface
    {
        if (!$this->getFactory()->isMultiCountryEnabled()) {
            return $searchQuery;
        }

        if (array_key_exists(CatalogConfig::PRODUCT_ABSTRACT_SELLABLE_FACET_NAME, $requestParameters)) {
            return $searchQuery;
        }

        $sellableValue = strtolower($this->getSellableCountryCode());
        $boolQuery = $this->getBoolQuery($searchQuery->getSearchQuery());
        $sellableFacetTermQuery = $this->createTermQuery(
            PageIndexMap::STRING_FACET_FACET_NAME,
            CatalogConfig::PRODUCT_ABSTRACT_SELLABLE_FACET_NAME
        );
        $sellableFacetFilter = $this->createNestedFilter($sellableFacetTermQuery);
        $sellableFacetValueTermQuery = $this->createTermQuery(
            PageIndexMap::STRING_FACET_FACET_VALUE,
            $sellableValue
        );
        $sellableFacetValueFilter = $this->createNestedFilter($sellableFacetValueTermQuery);

        $boolQuery->addFilter($sellableFacetFilter);
        $boolQuery->addFilter($sellableFacetValueFilter);

        return $searchQuery;
    }

    /**
     * @return string
     */
    private function getSellableCountryCode(): string
    {
        return strtolower($this->getFactory()->getStore()->getCurrentCountry());
    }

    /**
     * @param \Elastica\Query $query
     *
     * @throws \InvalidArgumentException
     *
     * @return \Elastica\Query\BoolQuery
     */
    private function getBoolQuery(Query $query): BoolQuery
    {
        $boolQuery = $query->getQuery();
        if (!$boolQuery instanceof BoolQuery) {
            throw new InvalidArgumentException(sprintf(
                'Sellable Query Expander available only with %s, got: %s',
                BoolQuery::class,
                get_class($boolQuery)
            ));
        }

        return $boolQuery;
    }

    /**
     * @param string $field
     * @param string $value
     *
     * @return \Elastica\Query\Term
     */
    private function createTermQuery(string $field, string $value): Term
    {
        return (new Term())
            ->setTerm($field, $value);
    }

    /**
     * @param \Elastica\Query\Term $termQuery
     *
     * @return \Elastica\Query\Nested
     */
    private function createNestedFilter(Term $termQuery): Nested
    {
        return (new Nested())
            ->setQuery($termQuery)
            ->setPath(PageIndexMap::STRING_FACET);
    }
}
