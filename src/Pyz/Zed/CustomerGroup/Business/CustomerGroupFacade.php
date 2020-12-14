<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\CustomerGroup\Business;

use Generated\Shared\Transfer\CustomerGroupTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Zed\CustomerGroup\Business\CustomerGroupFacade as SprykerCustomerGroupFacade;

/**
 * @method \Pyz\Zed\CustomerGroup\Business\CustomerGroupBusinessFactory getFactory()
 */
class CustomerGroupFacade extends SprykerCustomerGroupFacade implements CustomerGroupFacadeInterface
{
    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerGroupTransfer|null
     */
    public function assignCustomerToDefaultGroupByCustomerType(CustomerTransfer $customerTransfer): ?CustomerGroupTransfer
    {
        return $this->getFactory()->createCustomerGroupAssigner()->assignCustomerToDefaultGroupByCustomerType($customerTransfer);
    }
}
