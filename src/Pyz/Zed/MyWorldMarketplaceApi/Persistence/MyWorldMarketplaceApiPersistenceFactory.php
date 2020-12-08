<?php

namespace Pyz\Zed\MyWorldMarketplaceApi\Persistence;

use Orm\Zed\Sales\Persistence\SpySalesOrderQuery;
use Pyz\Zed\MyWorldMarketplaceApi\MyWorldMarketplaceApiDependencyProvider;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Pyz\Zed\MyWorldMarketplaceApi\MyWorldMarketplaceApiConfig getConfig()
 */
class MyWorldMarketplaceApiPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderQuery
     */
    public function getSalesOrderPropelQuery(): SpySalesOrderQuery
    {
        return $this->getProvidedDependency(MyWorldMarketplaceApiDependencyProvider::PROPEL_QUERY_SALES_ORDER);
    }
}
