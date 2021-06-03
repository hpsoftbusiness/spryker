<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MyWorldMarketplaceApi\Communication\Plugin\Customer;

use ArrayObject;
use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Zed\Customer\Dependency\Plugin\CustomerTransferExpanderPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Pyz\Zed\MyWorldMarketplaceApi\Communication\MyWorldMarketplaceApiCommunicationFactory getFactory()
 * @method \Pyz\Zed\MyWorldMarketplaceApi\Business\MyWorldMarketplaceApiFacadeInterface getFacade()
 * @method \Pyz\Zed\MyWorldMarketplaceApi\MyWorldMarketplaceApiConfig getConfig()
 */
class MyWorldBalancesCustomerTransferExpanderPlugin extends AbstractPlugin implements CustomerTransferExpanderPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function expandTransfer(CustomerTransfer $customerTransfer): CustomerTransfer
    {
        if ($this->getFactory()->getSsoClient()->isSsoLoginEnabled()) {
            $balances = $this->getFactory()->getMyWorldMarketplaceApiClient()->getCustomerBalanceByCurrency(
                $customerTransfer
            );
            $customerTransfer->setBalances(new ArrayObject($balances));
        }

        return $customerTransfer;
    }
}
