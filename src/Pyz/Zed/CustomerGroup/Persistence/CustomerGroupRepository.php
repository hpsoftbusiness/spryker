<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\CustomerGroup\Persistence;

use Generated\Shared\Transfer\CustomerGroupTransfer;
use Orm\Zed\CustomerGroup\Persistence\SpyCustomerGroupQuery;
use Spryker\Zed\CustomerGroup\Persistence\CustomerGroupRepository as SprykerCustomerGroupRepository;

class CustomerGroupRepository extends SprykerCustomerGroupRepository implements CustomerGroupRepositoryInterface
{
    /**
     * @param string $customerGroupName
     *
     * @return \Generated\Shared\Transfer\CustomerGroupTransfer|null
     */
    public function findCustomerGroupByName(string $customerGroupName): ?CustomerGroupTransfer
    {
        $spyCustomerGroup = SpyCustomerGroupQuery::create()
            ->filterByName($customerGroupName)
            ->findOne();

        if (!$spyCustomerGroup) {
            return null;
        }

        return (new CustomerGroupTransfer())->fromArray($spyCustomerGroup->toArray(), true);
    }
}
