<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Sales\Communication;

use Pyz\Zed\Sales\Communication\Form\AddressForm;
use Pyz\Zed\Sales\Communication\Form\DataProvider\AddressFormDataProvider;
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
            $this->getCountryFacade()
        );
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
