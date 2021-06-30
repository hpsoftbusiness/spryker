<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\CustomerGroup\Business;

use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Zed\CustomerGroup\Business\CustomerGroupFacadeInterface as SprykerCustomerGroupFacadeInterface;

interface CustomerGroupFacadeInterface extends SprykerCustomerGroupFacadeInterface
{
    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return void
     */
    public function assignCustomerToDefaultGroups(CustomerTransfer $customerTransfer): void;

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return void
     */
    public function removeCustomerFromAllGroups(CustomerTransfer $customerTransfer): void;
}
