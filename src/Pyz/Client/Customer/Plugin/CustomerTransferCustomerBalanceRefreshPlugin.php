<?php


namespace Pyz\Client\Customer\Plugin;


use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Client\Customer\Dependency\Plugin\CustomerSessionGetPluginInterface;
use Spryker\Client\Kernel\AbstractPlugin;

class CustomerTransferCustomerBalanceRefreshPlugin extends AbstractPlugin implements CustomerSessionGetPluginInterface
{
    public function execute(CustomerTransfer $customerTransfer)
    {
        // TODO: Implement execute() method.
    }
}
