<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Glue\CatalogSearchRestApi;

use Pyz\Glue\CatalogSearchRestApi\Processor\Mapper\CatalogSearchResourceMapper;
use Spryker\Glue\CatalogSearchRestApi\CatalogSearchRestApiFactory as SprykerCatalogSearchRestApiFactory;
use Spryker\Glue\CatalogSearchRestApi\Processor\Mapper\CatalogSearchResourceMapperInterface;

class CatalogSearchRestApiFactory extends SprykerCatalogSearchRestApiFactory
{
    /**
     * @return \Spryker\Glue\CatalogSearchRestApi\Processor\Mapper\CatalogSearchResourceMapperInterface
     */
    public function createCatalogSearchResourceMapper(): CatalogSearchResourceMapperInterface
    {
        return new CatalogSearchResourceMapper(
            $this->getCurrencyClient()
        );
    }
}
