<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\CustomerGroup\Business;

use Generated\Shared\Transfer\CustomerGroupTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Zed\CustomerGroup\Business\CustomerGroupFacadeInterface as SprykerCustomerGroupFacadeInterface;

interface CustomerGroupFacadeInterface extends SprykerCustomerGroupFacadeInterface
{
    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerGroupTransfer|null
     */
    public function assignCustomerToDefaultGroupByCustomerType(CustomerTransfer $customerTransfer): ?CustomerGroupTransfer;
}
