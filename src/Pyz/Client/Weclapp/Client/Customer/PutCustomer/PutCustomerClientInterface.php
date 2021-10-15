<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\Weclapp\Client\Customer\PutCustomer;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\WeclappCustomerTransfer;

interface PutCustomerClientInterface
{
    /**
     * Put customer
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     * @param \Generated\Shared\Transfer\WeclappCustomerTransfer $weclappCustomerTransfer
     *
     * @return void
     */
    public function putCustomer(
        CustomerTransfer $customerTransfer,
        WeclappCustomerTransfer $weclappCustomerTransfer
    ): void;
}
