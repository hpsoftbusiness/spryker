<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\Customer\Plugin;

use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Client\Customer\Plugin\CustomerTransferSessionRefreshPlugin as SprykerCustomerTransferSessionRefreshPlugin;

class CustomerTransferSessionRefreshPlugin extends SprykerCustomerTransferSessionRefreshPlugin
{
    /**
     * {@inheritDoc}
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return void
     */
    public function execute(CustomerTransfer $customerTransfer)
    {
        if ($customerTransfer->getIsDirty()) {
            $customerBalanceTransfer = $customerTransfer->getCustomerBalance();
            $customerTransfer = $this->getClient()->getCustomerByEmail($customerTransfer);
            $customerTransfer->setIsDirty(false);
            $customerTransfer->setCustomerBalance($customerBalanceTransfer);
            $this->getClient()->setCustomer($customerTransfer);
        }
    }
}
