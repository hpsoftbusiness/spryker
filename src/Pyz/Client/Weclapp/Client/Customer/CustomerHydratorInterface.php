<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\Weclapp\Client\Customer;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\WeclappCustomerTransfer;

interface CustomerHydratorInterface
{
    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     * @param \Generated\Shared\Transfer\WeclappCustomerTransfer|null $weclappCustomerTransfer
     *
     * @return \Generated\Shared\Transfer\WeclappCustomerTransfer
     */
    public function hydrateCustomerToWeclappCustomer(
        CustomerTransfer $customerTransfer,
        ?WeclappCustomerTransfer $weclappCustomerTransfer = null
    ): WeclappCustomerTransfer;

    /**
     * @param array $weclappCustomerData
     *
     * @return \Generated\Shared\Transfer\WeclappCustomerTransfer
     */
    public function mapWeclappDataToWeclappCustomer(array $weclappCustomerData): WeclappCustomerTransfer;
}
