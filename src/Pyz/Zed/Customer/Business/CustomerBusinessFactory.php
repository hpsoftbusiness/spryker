<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Customer\Business;

use Pyz\Zed\Customer\Business\Anonymizer\CustomerAnonymizer;
use Pyz\Zed\Customer\Business\Customer\Address;
use Pyz\Zed\Customer\Business\Customer\Customer;
use Pyz\Zed\Customer\CustomerDependencyProvider;
use Spryker\Zed\Customer\Business\CustomerBusinessFactory as SprykerCustomerBusinessFactory;

/**
 * @method \Pyz\Zed\Customer\CustomerConfig getConfig()
 * @method \Spryker\Zed\Customer\Persistence\CustomerQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Customer\Persistence\CustomerEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\Customer\Persistence\CustomerRepositoryInterface getRepository()
 */
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
            $this->createCustomerPasswordPolicyValidator(),
            $this->getPostCustomerRegistrationPlugins(),
            $this->getPostCustomerCreatePlugins(),
        );
    }

    /**
     * @return \Spryker\Zed\Customer\Business\Anonymizer\CustomerAnonymizer
     */
    public function createCustomerAnonymizer()
    {
        return new CustomerAnonymizer(
            $this->getQueryContainer(),
            $this->createCustomer(),
            $this->createAddress(),
            $this->getCustomerAnonymizerPlugins()
        );
    }

    /**
     * @return \Pyz\Zed\Customer\Dependency\Plugin\CustomerPostCreatePluginInterface[]
     */
    public function getPostCustomerCreatePlugins(): array
    {
        return $this->getProvidedDependency(CustomerDependencyProvider::PLUGINS_POST_CUSTOMER_CREATE);
    }

    /**
     * @return \Spryker\Zed\Customer\Business\Customer\AddressInterface
     */
    public function createAddress()
    {
        return new Address(
            $this->getQueryContainer(),
            $this->getCountryFacade(),
            $this->getLocaleFacade(),
            $this->createCustomerExpander(),
            $this->getRepository(),
            $this->getConfig()
        );
    }
}
