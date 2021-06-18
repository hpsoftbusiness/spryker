<?php
declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\CustomerGroupStorage\Persistence;

use Orm\Zed\CustomerGroup\Persistence\SpyCustomerGroupQuery;
use Orm\Zed\CustomerGroupStorage\Persistence\PyzCustomerGroupStorageQuery;
use Orm\Zed\ProductList\Persistence\SpyProductListQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Pyz\Zed\CustomerGroupStorage\Persistence\CustomerGroupStorageEntityManagerInterface getEntityManager()
 * @method \Pyz\Zed\CustomerGroupStorage\Persistence\CustomerGroupStorageQueryContainerInterface getQueryContainer()
 * @method \Pyz\Zed\CustomerGroupStorage\Persistence\CustomerGroupStorageRepositoryInterface getRepository()
 * @method \Pyz\Zed\CustomerGroupStorage\CustomerGroupStorageConfig getConfig()
 */
class CustomerGroupStoragePersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\CustomerGroup\Persistence\SpyCustomerGroupQuery
     */
    public function createCustomerGroupQuery(): SpyCustomerGroupQuery
    {
        return SpyCustomerGroupQuery::create();
    }

    /**
     * @return \Orm\Zed\CustomerGroupStorage\Persistence\PyzCustomerGroupStorageQuery
     */
    public function createCustomerGroupStorageQuery(): PyzCustomerGroupStorageQuery
    {
        return PyzCustomerGroupStorageQuery::create();
    }

    /**
     * @return \Orm\Zed\ProductList\Persistence\SpyProductListQuery
     */
    public function createProductListQuery(): SpyProductListQuery
    {
        return SpyProductListQuery::create();
    }
}
