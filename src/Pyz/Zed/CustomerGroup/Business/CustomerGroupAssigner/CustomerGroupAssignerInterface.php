<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\CustomerGroup\Business\CustomerGroupAssigner;

use Generated\Shared\Transfer\CustomerTransfer;

interface CustomerGroupAssignerInterface
{
    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return void
     */
    public function assignCustomerToDefaultGroups(CustomerTransfer $customerTransfer): void;
}
