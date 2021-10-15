<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\Weclapp\Client\Customer\PostCustomer;

use Generated\Shared\Transfer\CustomerTransfer;

interface PostCustomerClientInterface
{
    /**
     * Post customer
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return void
     */
    public function postCustomer(CustomerTransfer $customerTransfer): void;
}
