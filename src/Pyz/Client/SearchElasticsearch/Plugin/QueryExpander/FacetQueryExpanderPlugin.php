<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\SearchElasticsearch\Plugin\QueryExpander;

use Spryker\Client\SearchElasticsearch\Config\FacetConfigInterface;
use Spryker\Client\SearchElasticsearch\Plugin\QueryExpander\FacetQueryExpanderPlugin as SprykerFacetQueryExpanderPlugin;

/**
 * @method \Pyz\Client\SearchElasticsearch\SearchElasticsearchFactory getFactory()
 */
class FacetQueryExpanderPlugin extends SprykerFacetQueryExpanderPlugin
{
    /**
     * @param \Spryker\Client\SearchElasticsearch\Config\FacetConfigInterface $facetConfig
     * @param array $requestParameters
     *
     * @return \Elastica\Query\AbstractQuery[]
     */
    protected function getFacetFilters(FacetConfigInterface $facetConfig, array $requestParameters = []): array
    {
        $facetFilters = [];
        $activeFacetConfigTransfers = $facetConfig->getActive($requestParameters);

        foreach ($activeFacetConfigTransfers as $facetConfigTransfer) {
            $filterValue = $requestParameters[$facetConfigTransfer->getParameterName()] ?? null;
            if (is_array($filterValue) &&
                in_array(
                    $facetConfigTransfer->getName(),
                    $this->getFactory()->getConfig()->getCustomFacedQueryNamesForArrayFilter()
                )) {
                foreach ($filterValue as $value) {
                    $query = $this->createFacetQuery($facetConfigTransfer, $value);

                    if ($query !== null) {
                        $facetFilters[$facetConfigTransfer->getName() . $value] = $query;
                    }
                }
            } else {
                $query = $this->createFacetQuery($facetConfigTransfer, $filterValue);

                if ($query !== null) {
                    $facetFilters[$facetConfigTransfer->getName()] = $query;
                }
            }
        }

        return $facetFilters;
    }
}
