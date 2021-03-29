<?php

namespace Pyz\Zed\ProductApi\Persistence;

use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\ProductApi\ProductApiDependencyProvider;

/**
 * @method \Pyz\Zed\ProductApi\ProductApiConfig getConfig()
 * @method \Pyz\Zed\ProductApi\Persistence\ProductApiQueryContainerInterface getQueryContainer()
 */
class ProductApiPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function createProductAbstractQuery()
    {
        return SpyProductAbstractQuery::create();
    }
}
