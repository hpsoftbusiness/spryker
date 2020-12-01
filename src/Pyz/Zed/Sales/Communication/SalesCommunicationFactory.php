<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Sales\Communication;

use Pyz\Zed\Sales\Communication\Form\AddressForm;
use Pyz\Zed\Sales\Communication\Form\DataProvider\AddressFormDataProvider;
use Pyz\Zed\Sales\Dependency\Facade\SalesToCountryInterface;
use Pyz\Zed\Sales\SalesDependencyProvider;
use Spryker\Shared\Kernel\Store;
use Spryker\Zed\Sales\Communication\SalesCommunicationFactory as SprykerSalesCommunicationFactory;
use Symfony\Component\Form\FormInterface;

class SalesCommunicationFactory extends SprykerSalesCommunicationFactory
{
    /**
     * @return \Pyz\Zed\Sales\Communication\Form\DataProvider\AddressFormDataProvider
     */
    public function createAddressFormDataProvider(): AddressFormDataProvider
    {
        return new AddressFormDataProvider(
            $this->getQueryContainer(),
            $this->getCountryFacade(),
            $this->getStore()
        );
    }

    /**
     * @return \Pyz\Zed\Sales\Dependency\Facade\SalesToCountryInterface
     */
    public function getCountryFacade(): SalesToCountryInterface
    {
        return $this->getProvidedDependency(SalesDependencyProvider::FACADE_COUNTRY);
    }

    /**
     * @return \Spryker\Shared\Kernel\Store
     */
    public function getStore(): Store
    {
        return $this->getProvidedDependency(SalesDependencyProvider::STORE);
    }

    /**
     * @param array $formData
     * @param array $formOptions
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getAddressForm(array $formData = [], array $formOptions = []): FormInterface
    {
        return $this->getFormFactory()->create(AddressForm::class, $formData, $formOptions);
    }
}
