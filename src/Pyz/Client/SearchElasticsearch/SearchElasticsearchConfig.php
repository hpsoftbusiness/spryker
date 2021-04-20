<?php

namespace Pyz\Client\SearchElasticsearch;

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
}
