<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\MyWorldMarketplaceApi\Mapper;

use Exception;
use Generated\Shared\Transfer\CustomerBalanceTransfer;
use Generated\Shared\Transfer\CustomerTransfer;

class CustomerInformationMapper implements CustomerInformationMapperInterface
{
    /**
     * @see \Orm\Zed\Customer\Persistence\Map\SpyCustomerTableMap::COL_CUSTOMER_TYPE_CUSTOMER
     */
    protected const CUSTOMER_TYPE_CUSTOMER = 'Customer';

    /**
     * @see \Orm\Zed\Customer\Persistence\Map\SpyCustomerTableMap::COL_CUSTOMER_EMPLOYEE
     */
    protected const CUSTOMER_TYPE_EMPLOYEE = 'Employee';

    /**
     * @see \Orm\Zed\Customer\Persistence\Map\SpyCustomerTableMap::COL_CUSTOMER_MARKETER
     */
    protected const CUSTOMER_TYPE_MARKETER = 'Marketer';

    protected const SSO_CUSTOMER_TYPE_CUSTOMER = 1;
    protected const SSO_CUSTOMER_TYPE_EMPLOYEE = 2;
    protected const SSO_CUSTOMER_TYPE_MARKETER = 3;

    /**
     * @param array $data
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function mapDataToCustomerTransfer(array $data): CustomerTransfer
    {
        $customerTransfer = new CustomerTransfer();

        $customerTransfer->setEmail($data['Email'])
            ->setMyWorldCustomerId($data['CustomerID'])
            ->setMyWorldCustomerNumber($data['CustomerNumber'])
            ->setCardNumber($data['CardNumber'])
            ->setFirstName($data['Firstname'])
            ->setLastName($data['Lastname'])
            ->setDateOfBirth($data['BirthdayDate'])
            ->setPhone($data['MobilePhoneNumber'])
            ->setIsActive($data['Status'] === 'Active')
            ->setCountryId($data['CountryID'] ?? null)
            ->setCustomerType($data['CustomerType'])
            ->setCustomerBalance($this->mapCustomerBalance($data));

        return $customerTransfer;
    }

    /**
     * @param array $data
     *
     * @return \Generated\Shared\Transfer\CustomerBalanceTransfer
     */
    protected function mapCustomerBalance(array $data): CustomerBalanceTransfer
    {
        return (new CustomerBalanceTransfer())
            ->setAvailableCashbackAmount($data['AvailableCashbackAmount'] ?? null)
            ->setAvailableCashbackCurrency($data['AvailableCashbackCurrency'] ?? null)
            ->setAvailableShoppingPointAmount($data['AvailableShoppingPointAmount'] ?? null)
            ->setAvailableBenefitVoucherAmount($data['AvailableBenefitVoucherAmount'] ?? null)
            ->setAvailableBenefitVoucherCurrency($data['AvailableBenefitVoucherCurrency'] ?? null);
    }

    /**
     * @param int $customerType
     *
     * @throws \Exception
     *
     * @return string
     */
    protected function mapCustomerType(int $customerType): string
    {
        switch ($customerType) {
            case static::SSO_CUSTOMER_TYPE_CUSTOMER:
                return static::CUSTOMER_TYPE_CUSTOMER;
            case static::SSO_CUSTOMER_TYPE_EMPLOYEE:
                return static::CUSTOMER_TYPE_EMPLOYEE;
            case static::SSO_CUSTOMER_TYPE_MARKETER:
                return static::CUSTOMER_TYPE_MARKETER;
            default:
                throw new Exception(sprintf('Customer Type: "%s" not found.', $customerType));
        }
    }
}
