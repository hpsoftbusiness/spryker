<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Customer\Communication\Controller;

use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Zed\Customer\Communication\Controller\GatewayController as SprykerGatewayController;

class GatewayController extends SprykerGatewayController
{
    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerResponseTransfer
     */
    public function addAction(CustomerTransfer $customerTransfer)
    {
        return $this->getFacade()
            ->addCustomer($customerTransfer);
    }
}
