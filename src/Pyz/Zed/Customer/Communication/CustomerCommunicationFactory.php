<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Customer\Communication;

use Pyz\Zed\Customer\Communication\Form\AddressForm;
use Pyz\Zed\Customer\Communication\Form\DataProvider\AddressFormDataProvider;
use Spryker\Zed\Customer\Communication\CustomerCommunicationFactory as SprykerCustomerCommunicationFactory;
use Spryker\Zed\Customer\CustomerDependencyProvider;

/**
 * @method \Spryker\Zed\Customer\Persistence\CustomerQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Customer\CustomerConfig getConfig()
 * @method \Spryker\Zed\Customer\Persistence\CustomerEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\Customer\Persistence\CustomerRepositoryInterface getRepository()
 * @method \Spryker\Zed\Customer\Business\CustomerFacadeInterface getFacade()
 */
class CustomerCommunicationFactory extends SprykerCustomerCommunicationFactory
{
    /**
     * @param array $formData
     * @param array $formOptions
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createAddressForm(array $formData = [], array $formOptions = [])
    {
        return $this->getFormFactory()->create(AddressForm::class, $formData, $formOptions);
    }

    /**
     * @return \Pyz\Zed\Customer\Communication\Form\DataProvider\AddressFormDataProvider
     */
    public function createAddressFormDataProvider()
    {
        return new AddressFormDataProvider(
            $this->getProvidedDependency(CustomerDependencyProvider::FACADE_COUNTRY),
            $this->getQueryContainer(),
            $this->getStore()
        );
    }
}
