<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\SearchElasticsearch\Plugin\QueryExpander;

use Elastica\Query;
use Pyz\Shared\SearchElasticsearch\SearchElasticsearchConfig;
use Spryker\Client\SearchElasticsearch\Config\FacetConfigInterface;
use Spryker\Client\SearchElasticsearch\Plugin\QueryExpander\FacetQueryExpanderPlugin;

class CategoryFacetQueryExpanderPlugin extends FacetQueryExpanderPlugin
{
    /**
     * @param \Elastica\Query $query
     * @param \Spryker\Client\SearchElasticsearch\Config\FacetConfigInterface $facetConfig
     * @param \Elastica\Query\AbstractQuery[] $facetFilters
     * @param array $requestParameters
     *
     * @return void
     */
    protected function addFacetAggregationToQuery(Query $query, FacetConfigInterface $facetConfig, array $facetFilters, array $requestParameters): void
    {
        $boolQuery = $this->getBoolQuery($query);

        $activeFilters = $facetConfig->getActiveParamNames($requestParameters);

        $facetConfigTransfer = $facetConfig->get(SearchElasticsearchConfig::FACET_TYPE_CATEGORY);

        if (!$facetConfigTransfer) {
            return;
        }

        $facetAggregation = $this
            ->getFactory()
            ->createFacetAggregationFactory()
            ->create($facetConfigTransfer)
            ->createAggregation();

        $query->addAggregation($facetAggregation);

        if (in_array($facetConfigTransfer->getName(), $activeFilters, true)) {
            $globalAggregation = $this->createGlobalAggregation($facetFilters, $facetConfigTransfer, $boolQuery, $facetAggregation);

            $query->addAggregation($globalAggregation);
        }
    }
}
