<?php
declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\CustomerGroupStorage\Persistence;

use Orm\Zed\CustomerGroup\Persistence\SpyCustomerGroupQuery;
use Orm\Zed\CustomerGroupStorage\Persistence\PyzCustomerGroupStorageQuery;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Pyz\Zed\CustomerGroupStorage\Persistence\CustomerGroupStoragePersistenceFactory getFactory()
 */
class CustomerGroupStorageQueryContainer extends AbstractQueryContainer implements CustomerGroupStorageQueryContainerInterface
{
    /**
     * @param array $customerGroupIds
     *
     * @return \Orm\Zed\CustomerGroupStorage\Persistence\PyzCustomerGroupStorageQuery
     */
    public function queryCustomerGroupStorageByCustomerGroupIds(array $customerGroupIds): PyzCustomerGroupStorageQuery
    {
        $query = $this
            ->getFactory()
            ->createCustomerGroupStorageQuery()
            ->filterByFkCustomerGroup_In($customerGroupIds);

        if ($customerGroupIds === []) {
            $query->clear();
        }

        return $query->orderByFkCustomerGroup();
    }

    /**
     * @param array $customerGroupIds
     *
     * @return \Orm\Zed\CustomerGroup\Persistence\SpyCustomerGroupQuery
     */
    public function queryCustomerGroupByIds(array $customerGroupIds): SpyCustomerGroupQuery
    {
        $query = $this
            ->getFactory()
            ->createCustomerGroupQuery()
            ->filterByIdCustomerGroup_In($customerGroupIds);

        if ($customerGroupIds === []) {
            $query->clear();
        }

        return $query->orderByIdCustomerGroup();
    }
}
