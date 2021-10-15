<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\Weclapp\Client\Customer\GetCustomer;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\WeclappCustomerTransfer;

interface GetCustomerClientInterface
{
    /**
     * Get customer
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\WeclappCustomerTransfer|null
     */
    public function getCustomer(CustomerTransfer $customerTransfer): ?WeclappCustomerTransfer;
}
