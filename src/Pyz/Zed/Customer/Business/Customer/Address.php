<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Customer\Business\Customer;

use Generated\Shared\Transfer\AddressTransfer;
use Orm\Zed\Customer\Persistence\SpyCustomer;
use Orm\Zed\Customer\Persistence\SpyCustomerAddress;
use Pyz\Zed\Customer\CustomerConfig;
use Spryker\Zed\Customer\Business\Customer\Address as SprykerAddress;
use Spryker\Zed\Customer\Business\CustomerExpander\CustomerExpanderInterface;
use Spryker\Zed\Customer\Business\Exception\AddressNotFoundException;
use Spryker\Zed\Customer\Dependency\Facade\CustomerToCountryInterface;
use Spryker\Zed\Customer\Dependency\Facade\CustomerToLocaleInterface;
use Spryker\Zed\Customer\Persistence\CustomerQueryContainerInterface;
use Spryker\Zed\Customer\Persistence\CustomerRepositoryInterface;

class Address extends SprykerAddress
{
    /**
     * @var \Pyz\Zed\Customer\CustomerConfig
     */
    private $config;

    /**
     * @param \Spryker\Zed\Customer\Persistence\CustomerQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\Customer\Dependency\Facade\CustomerToCountryInterface $countryFacade
     * @param \Spryker\Zed\Customer\Dependency\Facade\CustomerToLocaleInterface $localeFacade
     * @param \Spryker\Zed\Customer\Business\CustomerExpander\CustomerExpanderInterface $customerExpander
     * @param \Spryker\Zed\Customer\Persistence\CustomerRepositoryInterface $customerRepository
     * @param \Pyz\Zed\Customer\CustomerConfig $config
     */
    public function __construct(
        CustomerQueryContainerInterface $queryContainer,
        CustomerToCountryInterface $countryFacade,
        CustomerToLocaleInterface $localeFacade,
        CustomerExpanderInterface $customerExpander,
        CustomerRepositoryInterface $customerRepository,
        CustomerConfig $config
    ) {
        parent::__construct($queryContainer, $countryFacade, $localeFacade, $customerExpander, $customerRepository);
        $this->config = $config;
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     * @param \Orm\Zed\Customer\Persistence\SpyCustomer $customer
     *
     * @return \Orm\Zed\Customer\Persistence\SpyCustomerAddress
     */
    protected function createCustomerAddress(AddressTransfer $addressTransfer, SpyCustomer $customer)
    {
        $addressTransfer->setUuid(null);
        $addressTransfer->setIdCustomerAddress(null);
        $addressTransfer->setStore($this->config->getStore());

        $addressEntity = new SpyCustomerAddress();
        $addressEntity->fromArray($addressTransfer->toArray());

        $fkCountry = $this->retrieveFkCountry($addressTransfer);
        $addressEntity->setFkCountry($fkCountry);

        $addressEntity->setCustomer($customer);
        $addressEntity->save();

        return $addressEntity;
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     * @param \Orm\Zed\Customer\Persistence\SpyCustomer $customer
     *
     * @throws \Spryker\Zed\Customer\Business\Exception\AddressNotFoundException
     *
     * @return \Orm\Zed\Customer\Persistence\SpyCustomerAddress
     */
    protected function updateCustomerAddress(AddressTransfer $addressTransfer, SpyCustomer $customer)
    {
        $addressTransfer->requireIdCustomerAddress();
        $addressTransfer->setStore($this->config->getStore());

        $addressEntity = $this->queryContainer
            ->queryAddressForCustomer($addressTransfer->getIdCustomerAddress(), $customer->getEmail())
            ->findOne();

        if (!$addressEntity) {
            throw new AddressNotFoundException(
                sprintf(
                    'Address not found for ID `%s` and customer email `%s`.',
                    $addressTransfer->getIdCustomerAddress(),
                    $customer->getEmail()
                )
            );
        }

        $fkCountry = $this->retrieveFkCountry($addressTransfer);

        $addressEntity->fromArray($addressTransfer->modifiedToArray());
        $addressEntity->setCustomer($customer);
        $addressEntity->setFkCountry($fkCountry);
        $addressEntity->save();

        return $addressEntity;
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     * @param \Orm\Zed\Customer\Persistence\SpyCustomer $customerEntity
     *
     * @return void
     */
    protected function updateCustomerDefaultAddresses(AddressTransfer $addressTransfer, SpyCustomer $customerEntity)
    {
        $addressTransfer->setStore($this->config->getStore());

        if ($customerEntity->getDefaultBillingAddress() === null || $addressTransfer->getIsDefaultBilling()) {
            $customerEntity->setDefaultBillingAddress($addressTransfer->getIdCustomerAddress());
        }

        if ($customerEntity->getDefaultShippingAddress() === null || $addressTransfer->getIsDefaultShipping()) {
            $customerEntity->setDefaultShippingAddress($addressTransfer->getIdCustomerAddress());
        }

        $customerEntity->save();
    }
}
