<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Customer\Business\Customer;

use Generated\Shared\Transfer\AddressesTransfer;
use Generated\Shared\Transfer\CountryTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Orm\Zed\Customer\Persistence\SpyCustomer;
use Orm\Zed\Customer\Persistence\SpyCustomerAddress;
use Propel\Runtime\Collection\ObjectCollection;
use Pyz\Zed\Customer\CustomerConfig;
use Spryker\Shared\Kernel\Store;
use Spryker\Zed\Customer\Business\Customer\Customer as SprykerCustomer;
use Spryker\Zed\Customer\Business\Customer\EmailValidatorInterface;
use Spryker\Zed\Customer\Business\CustomerExpander\CustomerExpanderInterface;
use Spryker\Zed\Customer\Business\CustomerPasswordPolicy\CustomerPasswordPolicyValidatorInterface;
use Spryker\Zed\Customer\Business\Exception\CustomerNotFoundException;
use Spryker\Zed\Customer\Business\ReferenceGenerator\CustomerReferenceGeneratorInterface;
use Spryker\Zed\Customer\Dependency\Facade\CustomerToMailInterface;
use Spryker\Zed\Customer\Persistence\CustomerQueryContainerInterface;
use Spryker\Zed\Locale\Persistence\LocaleQueryContainerInterface;

class Customer extends SprykerCustomer
{
    /**
     * @var \Pyz\Zed\Customer\Dependency\Plugin\CustomerPostCreatePluginInterface[]
     */
    protected $postCustomerCreatePlugins;

    /**
     * @var \Pyz\Zed\Customer\CustomerConfig
     */
    protected $customerConfig;

    /**
     * @param \Spryker\Zed\Customer\Persistence\CustomerQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\Customer\Business\ReferenceGenerator\CustomerReferenceGeneratorInterface $customerReferenceGenerator
     * @param \Pyz\Zed\Customer\CustomerConfig $customerConfig
     * @param \Spryker\Zed\Customer\Business\Customer\EmailValidatorInterface $emailValidator
     * @param \Spryker\Zed\Customer\Dependency\Facade\CustomerToMailInterface $mailFacade
     * @param \Spryker\Zed\Locale\Persistence\LocaleQueryContainerInterface $localeQueryContainer
     * @param \Spryker\Shared\Kernel\Store $store
     * @param \Spryker\Zed\Customer\Business\CustomerExpander\CustomerExpanderInterface $customerExpander
     * @param \Spryker\Zed\Customer\Business\CustomerPasswordPolicy\CustomerPasswordPolicyValidatorInterface $customerPasswordPolicyValidator
     * @param \Spryker\Zed\CustomerExtension\Dependency\Plugin\PostCustomerRegistrationPluginInterface[] $postCustomerRegistrationPlugins
     * @param \Pyz\Zed\Customer\Dependency\Plugin\CustomerPostCreatePluginInterface[] $postCustomerCreatePlugins
     */
    public function __construct(
        CustomerQueryContainerInterface $queryContainer,
        CustomerReferenceGeneratorInterface $customerReferenceGenerator,
        CustomerConfig $customerConfig,
        EmailValidatorInterface $emailValidator,
        CustomerToMailInterface $mailFacade,
        LocaleQueryContainerInterface $localeQueryContainer,
        Store $store,
        CustomerExpanderInterface $customerExpander,
        CustomerPasswordPolicyValidatorInterface $customerPasswordPolicyValidator,
        array $postCustomerRegistrationPlugins = [],
        array $postCustomerCreatePlugins = []
    ) {
        parent::__construct(
            $queryContainer,
            $customerReferenceGenerator,
            $customerConfig,
            $emailValidator,
            $mailFacade,
            $localeQueryContainer,
            $store,
            $customerExpander,
            $customerPasswordPolicyValidator,
            $postCustomerRegistrationPlugins
        );
        $this->postCustomerCreatePlugins = $postCustomerCreatePlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerResponseTransfer
     */
    public function add($customerTransfer)
    {
        $customerResponseTransfer = parent::add($customerTransfer);

        if (!$customerResponseTransfer->getIsSuccess()) {
            return $customerResponseTransfer;
        }

        $customerTransfer = $this->executePostCustomerCreatePlugins($customerResponseTransfer->getCustomerTransfer());
        $customerResponseTransfer->setCustomerTransfer($customerTransfer);

        return $customerResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    protected function executePostCustomerCreatePlugins(CustomerTransfer $customerTransfer): CustomerTransfer
    {
        foreach ($this->postCustomerCreatePlugins as $postCustomerCreatePlugin) {
            $customerTransfer = $postCustomerCreatePlugin->execute($customerTransfer);
        }

        return $customerTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @throws \Spryker\Zed\Customer\Business\Exception\CustomerNotFoundException
     *
     * @return \Orm\Zed\Customer\Persistence\SpyCustomer
     */
    protected function getCustomer(CustomerTransfer $customerTransfer)
    {
        $customerEntity = null;

        if ($customerTransfer->getIdCustomer()) {
            $customerEntity = $this->queryContainer->queryCustomerById($customerTransfer->getIdCustomer())
                ->findOne();
        } elseif ($customerTransfer->getMyWorldCustomerId()) {
            $customerEntity = $this->queryContainer->queryCustomers()
                ->filterByMyWorldCustomerId($customerTransfer->getMyWorldCustomerId())
                ->findOne();
        } elseif ($customerTransfer->getEmail()) {
            $customerEntity = $this->queryContainer->queryCustomerByEmail($customerTransfer->getEmail())
                ->findOne();
        } elseif ($customerTransfer->getRestorePasswordKey()) {
            $customerEntity = $this->queryContainer->queryCustomerByRestorePasswordKey(
                $customerTransfer->getRestorePasswordKey()
            )
                ->findOne();
        }

        if ($customerEntity !== null) {
            return $customerEntity;
        }

        throw new CustomerNotFoundException(
            sprintf(
                'Customer not found by either ID `%s`, email `%s` or restore password key `%s`.',
                $customerTransfer->getIdCustomer(),
                $customerTransfer->getEmail(),
                $customerTransfer->getRestorePasswordKey()
            )
        );
    }

    /**
     * @param \Orm\Zed\Customer\Persistence\SpyCustomerAddress $addressEntity
     *
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    protected function entityToTransfer(SpyCustomerAddress $addressEntity)
    {
        $addressTransfer = parent::entityToTransfer($addressEntity);

        $countryTransfer = (new CountryTransfer())
            ->setName($addressEntity->getCountry()->getName());
        $addressTransfer->setCountry($countryTransfer);

        return $addressTransfer;
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection $addressEntities
     * @param \Orm\Zed\Customer\Persistence\SpyCustomer $customer
     *
     * @return \Generated\Shared\Transfer\AddressesTransfer
     */
    protected function entityCollectionToTransferCollection(ObjectCollection $addressEntities, SpyCustomer $customer)
    {
        $addressCollection = new AddressesTransfer();

        foreach ($addressEntities as $address) {
            $addressTransfer = $this->entityToTransfer($address);
            if ($addressTransfer->getStore() === $this->customerConfig->getStore()) {
                if ($customer->getDefaultBillingAddress() === $address->getIdCustomerAddress()) {
                    $addressTransfer->setIsDefaultBilling(true);
                }
                if ($customer->getDefaultShippingAddress() === $address->getIdCustomerAddress()) {
                    $addressTransfer->setIsDefaultShipping(true);
                }
                $addressCollection->addAddress($addressTransfer);
            }
        }

        return $addressCollection;
    }
}
