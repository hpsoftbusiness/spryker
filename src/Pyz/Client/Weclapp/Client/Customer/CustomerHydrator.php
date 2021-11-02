<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\Weclapp\Client\Customer;

use ArrayObject;
use DateTime;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\WeclappCustomerAddressTransfer;
use Generated\Shared\Transfer\WeclappCustomerCustomAttributeTransfer;
use Generated\Shared\Transfer\WeclappCustomerTransfer;
use Orm\Zed\Customer\Persistence\Map\SpyCustomerTableMap;
use Pyz\Client\Weclapp\WeclappConfig;

class CustomerHydrator implements CustomerHydratorInterface
{
    protected const PARTY_TYPE = 'PERSON';
    protected const SALES_CHANNEL = 'GROSS1';
    protected const SALUTATION_MR = 'MR';
    protected const SALUTATION_MRS = 'MRS';

    /**
     * @var \Pyz\Client\Weclapp\WeclappConfig
     */
    protected $weclappConfig;

    /**
     * @param \Pyz\Client\Weclapp\WeclappConfig $weclappConfig
     */
    public function __construct(WeclappConfig $weclappConfig)
    {
        $this->weclappConfig = $weclappConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     * @param \Generated\Shared\Transfer\WeclappCustomerTransfer|null $weclappCustomerTransfer
     *
     * @return \Generated\Shared\Transfer\WeclappCustomerTransfer
     */
    public function hydrateCustomerToWeclappCustomer(
        CustomerTransfer $customerTransfer,
        ?WeclappCustomerTransfer $weclappCustomerTransfer = null
    ): WeclappCustomerTransfer {
        if (!$weclappCustomerTransfer) {
            $weclappCustomerTransfer = new WeclappCustomerTransfer();
        }

        $weclappCustomerTransfer->setPartyType(static::PARTY_TYPE)
            ->setPersonCompany($this->getNonEmptyValueOrDefault(
                $customerTransfer->getCompany(),
                $weclappCustomerTransfer->getPersonCompany()
            ))
            ->setFirstName($this->getNonEmptyValueOrDefault(
                $customerTransfer->getFirstName(),
                $weclappCustomerTransfer->getFirstName()
            ))
            ->setLastName($customerTransfer->getLastNameOrFail())
            ->setCustomerNumber($customerTransfer->getCustomerReferenceOrFail())
            ->setEmail($this->getNonEmptyValueOrDefault(
                $customerTransfer->getEmail(),
                $weclappCustomerTransfer->getEmail()
            ))
            ->setBirthDate(
                $this->mapToWeclappBirthDate($customerTransfer)
                ?? $weclappCustomerTransfer->getBirthDate()
            )
            ->setMobilePhone1($this->getNonEmptyValueOrDefault(
                $customerTransfer->getPhone(),
                $weclappCustomerTransfer->getMobilePhone1()
            ))
            ->setReferenceNumber($this->getNonEmptyValueOrDefault(
                $customerTransfer->getCustomerReference(),
                $weclappCustomerTransfer->getReferenceNumber()
            ))
            ->setOptInPhone(false)
            ->setOptInSms(false)
            ->setOptIn(true)
            ->setOptInLetter(false)
            ->setUseCustomsTariffNumber(true)
            ->setSalesChannel(static::SALES_CHANNEL)
            ->setAddresses($this->mapToWeclappAddresses($customerTransfer, $weclappCustomerTransfer->getAddresses()))
            ->setCustomAttributes($this->mapToWeclappCustomAttributes($customerTransfer))
            ->setSalutation(
                $this->mapToWeclappSalutation($customerTransfer->getSalutation())
                ?? $weclappCustomerTransfer->getSalutation()
            )
            ->setBlocked(false)
            ->setDeliveryBlock(false)
            ->setInsolvent(false)
            ->setInsured(false)
            ->setResponsibleUserFixed(false)
            ->setSalesPartner(false);

        return $weclappCustomerTransfer;
    }

    /**
     * @param array $weclappCustomerData
     *
     * @return \Generated\Shared\Transfer\WeclappCustomerTransfer
     */
    public function mapWeclappDataToWeclappCustomer(array $weclappCustomerData): WeclappCustomerTransfer
    {
        return (new WeclappCustomerTransfer())->fromArray($weclappCustomerData, true);
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return int|null
     */
    protected function mapToWeclappBirthDate(CustomerTransfer $customerTransfer): ?int
    {
        if (!$customerTransfer->getDateOfBirth()) {
            return null;
        }

        return (int)((new DateTime($customerTransfer->getDateOfBirth()))->format('Uv'));
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     * @param \ArrayObject $weclappAddresses
     *
     * @return \ArrayObject
     */
    protected function mapToWeclappAddresses(
        CustomerTransfer $customerTransfer,
        ArrayObject $weclappAddresses
    ): ArrayObject {
        if (!$customerTransfer->getAddresses()) {
            return $weclappAddresses;
        }

        foreach ($customerTransfer->getAddresses()->getAddresses() as $key => $address) {
            $weclappAddress = $weclappAddresses[$key] ?? new WeclappCustomerAddressTransfer();
            $weclappAddress->setFirstName($address->getFirstName())
                ->setLastName($address->getLastName())
                ->setCountryCode($address->getCountry()->getIso2Code())
                ->setCity($address->getCity())
                ->setStreet1($address->getAddress1() . ' ' . $address->getAddress2())
                ->setStreet2($address->getAddress3())
                ->setZipcode($address->getZipCode())
                ->setDeliveryAddress((int)$address->getIdCustomerAddress() === (int)$customerTransfer->getDefaultShippingAddress())
                ->setInvoiceAddress((int)$address->getIdCustomerAddress() === (int)$customerTransfer->getDefaultBillingAddress())
                ->setPrimeAddress($key === 0);

            $weclappAddresses[$key] = $weclappAddress;
        }

        return $weclappAddresses;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \ArrayObject
     */
    protected function mapToWeclappCustomAttributes(CustomerTransfer $customerTransfer): ArrayObject
    {
        $weclappCustomAttributes = new ArrayObject();

        $myWorldCustomerIdWeclappCustomAttribute = new WeclappCustomerCustomAttributeTransfer();
        $myWorldCustomerIdWeclappCustomAttribute->setAttributeDefinitionId(
            $this->weclappConfig->getCustomAttributeKeyMyWorldCustomerId()
        )
            ->setStringValue($customerTransfer->getMyWorldCustomerId());
        $weclappCustomAttributes[] = $myWorldCustomerIdWeclappCustomAttribute;

        $myWorldCashbackIdWeclappCustomAttribute = new WeclappCustomerCustomAttributeTransfer();
        $myWorldCashbackIdWeclappCustomAttribute->setAttributeDefinitionId(
            $this->weclappConfig->getCustomAttributeKeyCashbackId()
        )
            ->setStringValue($customerTransfer->getMyWorldCustomerNumber());
        $weclappCustomAttributes[] = $myWorldCashbackIdWeclappCustomAttribute;

        $cashbackCardNumberWeclappCustomAttribute = new WeclappCustomerCustomAttributeTransfer();
        $cashbackCardNumberWeclappCustomAttribute->setAttributeDefinitionId(
            $this->weclappConfig->getCustomAttributeKeyCashbackCardNumber()
        )
            ->setStringValue($customerTransfer->getCardNumber());
        $weclappCustomAttributes[] = $cashbackCardNumberWeclappCustomAttribute;

        return $weclappCustomAttributes;
    }

    /**
     * @param string|null $salutation
     *
     * @return string|null
     */
    protected function mapToWeclappSalutation(?string $salutation): ?string
    {
        switch ($salutation) {
            case SpyCustomerTableMap::COL_SALUTATION_MR:
            case SpyCustomerTableMap::COL_SALUTATION_DR:
                return static::SALUTATION_MR;
            case SpyCustomerTableMap::COL_SALUTATION_MS:
            case SpyCustomerTableMap::COL_SALUTATION_MRS:
                return static::SALUTATION_MRS;
            default:
                return null;
        }
    }

    /**
     * @param mixed $value
     * @param mixed $defaultValue
     *
     * @return mixed
     */
    protected function getNonEmptyValueOrDefault($value, $defaultValue)
    {
        if ($value === null || $value === '') {
            return $defaultValue;
        }

        return $value;
    }
}
