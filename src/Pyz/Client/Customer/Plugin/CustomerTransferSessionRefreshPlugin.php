<?php

namespace Pyz\Client\Customer\Plugin;

use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Client\Customer\Plugin\CustomerTransferSessionRefreshPlugin as SprykerCustomerTransferSessionRefreshPlugin;

/**
 * @method \Pyz\Client\Customer\CustomerFactory getFactory() : AbstractFactory
 */
class CustomerTransferSessionRefreshPlugin extends SprykerCustomerTransferSessionRefreshPlugin
{
    /**
     * {@inheritDoc}
     * - Expands the provided CustomerTransfer with customer balance and stores it to session.
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return void
     */
    public function execute(CustomerTransfer $customerTransfer)
    {
        if ($customerTransfer->getIsDirty()) {
            $customerTransfer = $this->getClient()->getCustomerByEmail($customerTransfer);
            $customerTransfer = $this->getFactory()->getMyWorldMarketplaceApiClient()->getCustomerInformationByCustomerNumberOrId($customerTransfer);
            $customerTransfer->setIsDirty(false);
            $this->getClient()->setCustomer($customerTransfer);
        }
    }
}
