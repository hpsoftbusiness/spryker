<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\SearchElasticsearch;

use Spryker\Client\SearchElasticsearch\SearchElasticsearchFactory as SprykerSearchElasticsearchFactory;
use Spryker\Shared\Kernel\Store;
use Spryker\Shared\SearchElasticsearch\Dependency\Client\SearchElasticsearchToLocaleClientInterface;

/**
 * @method \Pyz\Client\SearchElasticsearch\SearchElasticsearchConfig getConfig()
 */
class SearchElasticsearchFactory extends SprykerSearchElasticsearchFactory
{
    /**
     * @return \Spryker\Shared\SearchElasticsearch\Dependency\Client\SearchElasticsearchToLocaleClientInterface
     */
    public function getLocaleClient(): SearchElasticsearchToLocaleClientInterface
    {
        return parent::getLocaleClient();
    }

    /**
     * @return \Spryker\Shared\Kernel\Store
     */
    public function getStore(): Store
    {
        return $this->getProvidedDependency(SearchElasticsearchDependencyProvider::STORE);
    }

    /**
     * @return bool
     */
    public function isMultiCountryEnabled(): bool
    {
        return $this->getConfig()->isMultiCountryEnabled();
    }
}
