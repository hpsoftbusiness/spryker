<?php
declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\CustomerGroup\Communication\Controller;

use Generated\Shared\Transfer\CustomerResponseTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractGatewayController;

/**
 * @method \Pyz\Zed\CustomerGroup\Business\CustomerGroupFacadeInterface getFacade()
 */
class GatewayController extends AbstractGatewayController
{
    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerResponseTransfer
     */
    public function reassignCustomerGroupsAction(CustomerTransfer $customerTransfer): CustomerResponseTransfer
    {
        $this
            ->getFacade()
            ->removeCustomerFromAllGroups($customerTransfer);

        $this
            ->getFacade()
            ->assignCustomerToDefaultGroups($customerTransfer);

        return (new CustomerResponseTransfer())
            ->setCustomerTransfer($customerTransfer)
            ->setIsSuccess(true);
    }
}
