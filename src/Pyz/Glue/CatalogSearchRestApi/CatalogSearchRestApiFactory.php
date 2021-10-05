<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Glue\CatalogSearchRestApi;

use Pyz\Glue\CatalogSearchRestApi\Helper\CatalogCustomFieldsHelper;
use Pyz\Glue\CatalogSearchRestApi\Helper\CatalogCustomFieldsHelperInterface;
use Pyz\Glue\CatalogSearchRestApi\Processor\Mapper\CatalogSearchResourceMapper;
use Pyz\Glue\CatalogSearchRestApi\Processor\Mapper\CatalogSearchSuggestionsResourceMapper;
use Spryker\Glue\CatalogSearchRestApi\CatalogSearchRestApiFactory as SprykerCatalogSearchRestApiFactory;
use Spryker\Glue\CatalogSearchRestApi\Processor\Mapper\CatalogSearchResourceMapperInterface;
use Spryker\Glue\CatalogSearchRestApi\Processor\Mapper\CatalogSearchSuggestionsResourceMapperInterface;

class CatalogSearchRestApiFactory extends SprykerCatalogSearchRestApiFactory
{
    /**
     * @return \Spryker\Glue\CatalogSearchRestApi\Processor\Mapper\CatalogSearchResourceMapperInterface
     */
    public function createCatalogSearchResourceMapper(): CatalogSearchResourceMapperInterface
    {
        return new CatalogSearchResourceMapper(
            $this->getCurrencyClient(),
            $this->getProductAbstractExpanderPlugins()
        );
    }

    /**
     * @return \Spryker\Glue\CatalogSearchRestApi\Processor\Mapper\CatalogSearchSuggestionsResourceMapperInterface
     */
    public function createCatalogSearchSuggestionsResourceMapper(): CatalogSearchSuggestionsResourceMapperInterface
    {
        return new CatalogSearchSuggestionsResourceMapper($this->createCatalogCustomFieldsHelper());
    }

    /**
     * @return \Pyz\Glue\CatalogSearchRestApi\Helper\CatalogCustomFieldsHelperInterface
     */
    public function createCatalogCustomFieldsHelper(): CatalogCustomFieldsHelperInterface
    {
        return new CatalogCustomFieldsHelper();
    }

    /**
     * @return \Pyz\Glue\CatalogSearchRestApi\Dependency\Plugin\CatalogSearchAbstractProductExpanderPluginInterface[]
     */
    public function getProductAbstractExpanderPlugins(): array
    {
        return $this->getProvidedDependency(CatalogSearchRestApiDependencyProvider::PLUGINS_PRODUCT_ABSTRACT_EXPANDER);
    }
}
