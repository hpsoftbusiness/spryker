<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Sales\Communication;

use Pyz\Zed\Sales\Communication\Form\AddressForm;
use Pyz\Zed\Sales\Communication\Form\DataProvider\AddressFormDataProvider;
use Pyz\Zed\Sales\Communication\Table\CustomerOrdersTable;
use Pyz\Zed\Sales\Communication\Table\OrdersTable;
use Pyz\Zed\Sales\SalesDependencyProvider;
use Spryker\Shared\Kernel\Store;
use Spryker\Zed\Sales\Communication\SalesCommunicationFactory as SprykerSalesCommunicationFactory;
use Spryker\Zed\Sales\SalesDependencyProvider as SprykerSalesDependencyProvider;
use Symfony\Component\Form\FormInterface;

/**
 * @method \Pyz\Zed\Country\Business\CountryFacadeInterface getCountryFacade()
 */
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
     * @return \Pyz\Zed\Sales\Communication\Table\OrdersTable
     */
    public function createOrdersTable()
    {
        return new OrdersTable(
            $this->createOrdersTableQueryBuilder(),
            $this->getProvidedDependency(SprykerSalesDependencyProvider::FACADE_MONEY),
            $this->getProvidedDependency(SalesDependencyProvider::SERVICE_UTIL_SANITIZE),
            $this->getProvidedDependency(SalesDependencyProvider::SERVICE_DATE_FORMATTER),
            $this->getProvidedDependency(SalesDependencyProvider::FACADE_CUSTOMER),
            $this->getUserFacade(),
            $this->getSalesTablePlugins()
        );
    }

    /**
     * @param string $customerReference
     *
     * @return \Spryker\Zed\Sales\Communication\Table\CustomerOrdersTable
     */
    public function createCustomerOrdersTable($customerReference)
    {
        return new CustomerOrdersTable(
            $this->createOrdersTableQueryBuilder(),
            $this->getProvidedDependency(SprykerSalesDependencyProvider::FACADE_MONEY),
            $this->getProvidedDependency(SalesDependencyProvider::SERVICE_UTIL_SANITIZE),
            $this->getProvidedDependency(SalesDependencyProvider::SERVICE_DATE_FORMATTER),
            $this->getProvidedDependency(SalesDependencyProvider::FACADE_CUSTOMER),
            $customerReference,
            $this->getQueryContainer(),
            $this->getUserFacade(),
            $this->getSalesTablePlugins()
        );
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
