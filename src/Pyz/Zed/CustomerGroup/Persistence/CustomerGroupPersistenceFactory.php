<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\CustomerGroup\Persistence;

use Orm\Zed\CustomerGroupProductList\Persistence\SpyCustomerGroupToProductListQuery;
use Pyz\Zed\CustomerGroup\CustomerGroupDependencyProvider;
use Spryker\Zed\CustomerGroup\Persistence\CustomerGroupPersistenceFactory as SprykerCustomerGroupPersistenceFactory;

class CustomerGroupPersistenceFactory extends SprykerCustomerGroupPersistenceFactory
{
    /**
     * @return \Orm\Zed\CustomerGroupProductList\Persistence\SpyCustomerGroupToProductListQuery
     */
    public function createCustomerGroupToProductListQuery(): SpyCustomerGroupToProductListQuery
    {
        return $this->getProvidedDependency(CustomerGroupDependencyProvider::PROPEL_QUERY_CUSTOMER_GROUP_TO_PRODUCT_LIST);
    }
}
