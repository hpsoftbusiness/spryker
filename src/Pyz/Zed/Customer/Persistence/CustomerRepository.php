<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Customer\Persistence;

use Generated\Shared\Transfer\AddressCriteriaFilterTransfer;
use Generated\Shared\Transfer\CustomerCriteriaFilterTransfer;
use Orm\Zed\Customer\Persistence\SpyCustomerAddressQuery;
use Orm\Zed\Customer\Persistence\SpyCustomerQuery;
use Spryker\Zed\Customer\Persistence\CustomerRepository as SprykerCustomerRepository;

/**
 * @method \Pyz\Zed\Customer\Persistence\CustomerPersistenceFactory getFactory()
 */
class CustomerRepository extends SprykerCustomerRepository
{
    /**
     * @param \Generated\Shared\Transfer\AddressCriteriaFilterTransfer $addressCriteriaFilterTransfer
     *
     * @return \Orm\Zed\Customer\Persistence\SpyCustomerAddressQuery
     */
    protected function buildAddressConditionsByCriteria(
        AddressCriteriaFilterTransfer $addressCriteriaFilterTransfer
    ): SpyCustomerAddressQuery {
        $store = $this->getFactory()->getConfig()->getStore();
        $addressQuery = $this->getFactory()->createSpyCustomerAddressQuery()->joinWithCountry()->filterByStore($store);

        if ($addressCriteriaFilterTransfer->getIdCustomerAddress()) {
            $addressQuery->filterByIdCustomerAddress($addressCriteriaFilterTransfer->getIdCustomerAddress());
        }

        if ($addressCriteriaFilterTransfer->getFkCustomer()) {
            $addressQuery->filterByFkCustomer($addressCriteriaFilterTransfer->getFkCustomer());
        }

        return $addressQuery;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerCriteriaFilterTransfer $customerCriteriaFilterTransfer
     *
     * @return \Orm\Zed\Customer\Persistence\SpyCustomerQuery
     */
    protected function queryCustomersByCriteria(
        CustomerCriteriaFilterTransfer $customerCriteriaFilterTransfer
    ): SpyCustomerQuery {
        $query = parent::queryCustomersByCriteria($customerCriteriaFilterTransfer);

        if ($customerCriteriaFilterTransfer->getIdCustomers()) {
            $query->filterByIdCustomer_In($customerCriteriaFilterTransfer->getIdCustomers());
        }

        return $query;
    }
}
