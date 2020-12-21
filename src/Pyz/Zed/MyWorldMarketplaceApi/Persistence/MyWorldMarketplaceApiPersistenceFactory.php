<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MyWorldMarketplaceApi\Persistence;

use Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery;
use Pyz\Zed\MyWorldMarketplaceApi\MyWorldMarketplaceApiDependencyProvider;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Pyz\Zed\MyWorldMarketplaceApi\MyWorldMarketplaceApiConfig getConfig()
 * @method \Pyz\Zed\MyWorldMarketplaceApi\Persistence\MyWorldMarketplaceApiEntityManagerInterface getEntityManager()
 */
class MyWorldMarketplaceApiPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery
     */
    public function getSalesOrderItemPropelQuery(): SpySalesOrderItemQuery
    {
        return $this->getProvidedDependency(MyWorldMarketplaceApiDependencyProvider::PROPEL_QUERY_SALES_ORDER_ITEM);
    }
}
