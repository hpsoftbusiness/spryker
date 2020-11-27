<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\CustomerGroupProductList\Persistence;

use Orm\Zed\CustomerGroupProductList\Persistence\PyzCustomerGroupToProductListQuery;
use Orm\Zed\ProductList\Persistence\SpyProductListQuery;
use Pyz\Zed\CustomerGroupProductList\CustomerGroupProductListDependencyProvider;
use Pyz\Zed\CustomerGroupProductList\Persistence\Propel\Mapper\CustomerGroupProductListMapper;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Pyz\Zed\CustomerGroupProductList\Persistence\CustomerGroupProductListRepositoryInterface getRepository()
 * @method \Pyz\Zed\CustomerGroupProductList\Persistence\CustomerGroupProductListEntityManagerInterface getEntityManager()
 */
class CustomerGroupProductListPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\CustomerGroupProductList\Persistence\PyzCustomerGroupToProductListQuery
     */
    public function createCustomerGroupToProductListQuery(): PyzCustomerGroupToProductListQuery
    {
        return PyzCustomerGroupToProductListQuery::create();
    }

    /**
     * @return \Orm\Zed\ProductList\Persistence\SpyProductListQuery
     */
    public function getProductListQuery(): SpyProductListQuery
    {
        return $this->getProvidedDependency(CustomerGroupProductListDependencyProvider::PROPEL_QUERY_PRODUCT_LIST);
    }

    /**
     * @return \Pyz\Zed\CustomerGroupProductList\Persistence\Propel\Mapper\CustomerGroupProductListMapper
     */
    public function createCustomerGroupProductListMapper(): CustomerGroupProductListMapper
    {
        return new CustomerGroupProductListMapper();
    }
}
