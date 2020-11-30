<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Customer\Business;

use Pyz\Zed\Customer\Business\Customer\Customer;
use Spryker\Zed\Customer\Business\CustomerBusinessFactory as SprykerCustomerBusinessFactory;

class CustomerBusinessFactory extends SprykerCustomerBusinessFactory
{
    /**
     * @return \Pyz\Zed\Customer\Business\Customer\Customer|\Spryker\Zed\Customer\Business\Customer\Customer|\Spryker\Zed\Customer\Business\Customer\CustomerInterface
     */
    public function createCustomer()
    {
        return new Customer(
            $this->getQueryContainer(),
            $this->createCustomerReferenceGenerator(),
            $this->getConfig(),
            $this->createEmailValidator(),
            $this->getMailFacade(),
            $this->getLocaleQueryContainer(),
            $this->getStore(),
            $this->createCustomerExpander(),
            $this->getPostCustomerRegistrationPlugins()
        );
    }
}
