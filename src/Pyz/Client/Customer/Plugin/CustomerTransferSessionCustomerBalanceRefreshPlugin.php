<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\Customer\Plugin;

use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Client\Customer\Dependency\Plugin\CustomerSessionGetPluginInterface;
use Spryker\Client\Kernel\AbstractPlugin;

/**
 * @method \Pyz\Client\Customer\CustomerClientInterface getClient()
 * @method \Pyz\Client\Customer\CustomerFactory getFactory()
 */
class CustomerTransferSessionCustomerBalanceRefreshPlugin extends AbstractPlugin implements
    CustomerSessionGetPluginInterface
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
        if (!$this->getFactory()->getConfig()->isSsoLoginEnabled()) {
            return;
        }

        if (!$customerTransfer->getCustomerBalance()) {
            $customerTransfer = $this->getFactory()
                ->getMyWorldMarketplaceApiClient()
                ->getCustomerInformationByCustomerNumberOrId($customerTransfer);
            $this->getClient()->setCustomer($customerTransfer);
        }
    }
}
