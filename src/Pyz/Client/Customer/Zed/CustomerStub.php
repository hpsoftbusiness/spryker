<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\Customer\Zed;

use Generated\Shared\Transfer\CustomerResponseTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Client\Customer\Zed\CustomerStub as SprykerCustomerStub;

class CustomerStub extends SprykerCustomerStub implements CustomerStubInterface
{
    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerResponseTransfer
     */
    public function create(CustomerTransfer $customerTransfer): CustomerResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\CustomerResponseTransfer $customerResponseTransfer */
        $customerResponseTransfer = $this->zedStub->call('/customer/gateway/add', $customerTransfer);

        return $customerResponseTransfer;
    }
}
