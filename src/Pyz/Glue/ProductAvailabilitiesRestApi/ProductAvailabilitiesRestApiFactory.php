<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Glue\ProductAvailabilitiesRestApi;

use Pyz\Glue\ProductAvailabilitiesRestApi\Processor\Mapper\AbstractProductAvailabilitiesResourceMapper;
use Pyz\Glue\ProductAvailabilitiesRestApi\Processor\Mapper\ConcreteProductAvailabilitiesResourceMapper;
use Spryker\Client\Locale\LocaleClientInterface;
use Spryker\Client\ProductStorage\ProductStorageClientInterface;
use Spryker\Glue\ProductAvailabilitiesRestApi\Processor\Mapper\AbstractProductAvailabilitiesResourceMapperInterface;
use Spryker\Glue\ProductAvailabilitiesRestApi\Processor\Mapper\ConcreteProductAvailabilitiesResourceMapperInterface;
use Spryker\Glue\ProductAvailabilitiesRestApi\ProductAvailabilitiesRestApiFactory as SprykerProductAvailabilitiesRestApiFactory;

class ProductAvailabilitiesRestApiFactory extends SprykerProductAvailabilitiesRestApiFactory
{
    /**
     * @return \Spryker\Glue\ProductAvailabilitiesRestApi\Processor\Mapper\ConcreteProductAvailabilitiesResourceMapperInterface
     */
    public function createConcreteProductsAvailabilitiesResourceMapper(): ConcreteProductAvailabilitiesResourceMapperInterface
    {
        return new ConcreteProductAvailabilitiesResourceMapper(
            $this->getClientProductStorage(),
            $this->getLocalClient()
        );
    }

    /**
     * @return \Spryker\Glue\ProductAvailabilitiesRestApi\Processor\Mapper\AbstractProductAvailabilitiesResourceMapperInterface
     */
    public function createAbstractProductsAvailabilitiesResourceMapper(): AbstractProductAvailabilitiesResourceMapperInterface
    {
        return new AbstractProductAvailabilitiesResourceMapper(
            $this->getClientProductStorage(),
            $this->getLocalClient()
        );
    }

    /**
     * @return \Spryker\Client\ProductStorage\ProductStorageClientInterface
     */
    public function getClientProductStorage(): ProductStorageClientInterface
    {
        return $this->getProvidedDependency(ProductAvailabilitiesRestApiDependencyProvider::PRODUCT_STORAGE_CLIENT);
    }

    /**
     * @return \Spryker\Client\Locale\LocaleClientInterface
     */
    public function getLocalClient(): LocaleClientInterface
    {
        return $this->getProvidedDependency(ProductAvailabilitiesRestApiDependencyProvider::LOCALE_CLIENT);
    }
}
