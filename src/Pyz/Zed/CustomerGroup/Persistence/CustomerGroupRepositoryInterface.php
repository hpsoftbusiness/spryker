<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\CustomerGroup\Persistence;

use Generated\Shared\Transfer\CustomerGroupTransfer;
use Spryker\Zed\CustomerGroup\Persistence\CustomerGroupRepositoryInterface as SprykerCustomerGroupRepositoryInterface;

interface CustomerGroupRepositoryInterface extends SprykerCustomerGroupRepositoryInterface
{
    /**
     * @param string $customerGroupName
     *
     * @return \Generated\Shared\Transfer\CustomerGroupTransfer|null
     */
    public function findCustomerGroupByName(string $customerGroupName): ?CustomerGroupTransfer;
}
