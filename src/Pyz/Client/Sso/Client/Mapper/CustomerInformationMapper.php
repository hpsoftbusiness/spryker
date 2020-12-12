<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\Sso\Client\Mapper;

use Exception;
use Generated\Shared\Transfer\CustomerBalanceTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Orm\Zed\Customer\Persistence\Map\SpyCustomerTableMap;

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

        $customerTransfer->setEmail($data['Data']['Email'])
            ->setMyWorldCustomerId($data['Data']['CustomerID'])
            ->setMyWorldCustomerNumber($data['Data']['CustomerNumber'])
            ->setCardNumber($data['Data']['CardNumber'])
            ->setFirstName($data['Data']['Firstname'])
            ->setLastName($data['Data']['Lastname'])
            ->setDateOfBirth($data['Data']['BirthdayDate'])
            ->setPhone($data['Data']['MobilePhoneNumber'])
            ->setIsActive($data['Data']['Status'] === 'Active')
            ->setCustomerType($this->mapCustomerType($data['Data']['CustomerType']))
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
            ->setAvailableCashbackAmount($data['Data']['AvailableCashbackAmount'] ?? null)
            ->setAvailableCashbackCurrency($data['Data']['AvailableCashbackCurrency'] ?? null)
            ->setAvailableShoppingPointAmount($data['Data']['AvailableShoppingPointAmount'] ?? null)
            ->setAvailableBenefitVoucherAmount($data['Data']['AvailableBenefitVoucherAmount'] ?? null)
            ->setAvailableBenefitVoucherCurrency($data['Data']['AvailableBenefitVoucherCurrency'] ?? null);
    }

    /**
     * @param int $customerType
     * @return string
     * @throws \Exception
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
