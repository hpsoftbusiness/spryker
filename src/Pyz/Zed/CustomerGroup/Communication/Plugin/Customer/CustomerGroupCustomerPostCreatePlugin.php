<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\CustomerGroup\Communication\Plugin\Customer;

use Generated\Shared\Transfer\CustomerTransfer;
use Pyz\Zed\Customer\Dependency\Plugin\CustomerPostCreatePluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Pyz\Zed\CustomerGroup\Business\CustomerGroupFacadeInterface getFacade() : AbstractFacade
 * @method \Pyz\Zed\CustomerGroup\CustomerGroupConfig getConfig()
 * @method \Pyz\Zed\CustomerGroup\Persistence\CustomerGroupQueryContainerInterface getQueryContainer()
 * @method \Pyz\Zed\CustomerGroup\Communication\CustomerGroupCommunicationFactory getFactory()
 */
class CustomerGroupCustomerPostCreatePlugin extends AbstractPlugin implements CustomerPostCreatePluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function execute(CustomerTransfer $customerTransfer): CustomerTransfer
    {
        $this->getFacade()->assignCustomerToDefaultGroupByCustomerType($customerTransfer);

        return $customerTransfer;
    }
}
