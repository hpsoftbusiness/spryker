<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\SearchElasticsearch;

use Pyz\Shared\Catalog\CatalogConfig as SharedCatalogConfig;
use Pyz\Shared\Country\CountryConstants;
use Spryker\Client\SearchElasticsearch\SearchElasticsearchConfig as SprykerSearchElasticsearchConfig;

class SearchElasticsearchConfig extends SprykerSearchElasticsearchConfig
{
    /**
     * @return bool
     */
    public function isMultiCountryEnabled(): bool
    {
        return $this->get(CountryConstants::IS_MULTI_COUNTRY_FEATURE_ENABLED);
    }

    /**
     * @return array
     */
    public function getCustomFacedQueryNamesForArrayFilter(): array
    {
        return [
            SharedCatalogConfig::PRODUCT_ABSTRACT_SELLABLE_FACET_NAME,
            SharedCatalogConfig::PRODUCT_ABSTRACT_CUSTOMER_GROUP_FACET_NAME,
            SharedCatalogConfig::PRODUCT_ABSTRACT_NOT_CUSTOMER_GROUP_FACET_NAME,
        ];
    }
}
