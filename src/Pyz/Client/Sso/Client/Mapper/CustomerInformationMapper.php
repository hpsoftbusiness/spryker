<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\Sso\Client\Mapper;

use Generated\Shared\Transfer\CustomerBalanceTransfer;
use Generated\Shared\Transfer\CustomerTransfer;

class CustomerInformationMapper implements CustomerInformationMapperInterface
{
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
            ->setCustomerType($data['Data']['CustomerType'] - 1)
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
}
