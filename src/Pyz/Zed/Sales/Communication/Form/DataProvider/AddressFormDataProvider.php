<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Sales\Communication\Form\DataProvider;

use Generated\Shared\Transfer\CountryCollectionTransfer;
use Generated\Shared\Transfer\CountryTransfer;
use Pyz\Zed\Sales\Communication\Form\AddressForm;
use Pyz\Zed\Sales\Dependency\Facade\SalesToCountryInterface;
use Spryker\Shared\Kernel\Store;
use Spryker\Zed\Sales\Communication\Form\DataProvider\AddressFormDataProvider as SprykerAddressFormDataProvider;
use Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface;

class AddressFormDataProvider extends SprykerAddressFormDataProvider
{
    /**
     * @var \Spryker\Shared\Kernel\Store
     */
    protected $store;

    /**
     * @var \Pyz\Zed\Sales\Dependency\Facade\SalesToCountryInterface
     */
    protected $countryFacade;

    /**
     * @param \Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface $salesQueryContainer
     * @param \Pyz\Zed\Sales\Dependency\Facade\SalesToCountryInterface $countryFacade
     * @param \Spryker\Shared\Kernel\Store $store
     */
    public function __construct(
        SalesQueryContainerInterface $salesQueryContainer,
        SalesToCountryInterface $countryFacade,
        Store $store
    ) {
        parent::__construct($salesQueryContainer, $countryFacade);
        $this->store = $store;
    }

    /**
     * @param int $idOrderAddress
     *
     * @return array
     */
    public function getData($idOrderAddress): array
    {
        $address = $this->salesQueryContainer->querySalesOrderAddressById($idOrderAddress)->findOne();

        return [
            AddressForm::FIELD_FIRST_NAME => $address->getFirstName(),
            AddressForm::FIELD_MIDDLE_NAME => $address->getMiddleName(),
            AddressForm::FIELD_LAST_NAME => $address->getLastName(),
            AddressForm::FIELD_EMAIL => $address->getEmail(),
            AddressForm::FIELD_ADDRESS_1 => $address->getAddress1(),
            AddressForm::FIELD_ADDRESS_2 => $address->getAddress2(),
            AddressForm::FIELD_COMPANY => $address->getCompany(),
            AddressForm::FIELD_CITY => $address->getCity(),
            AddressForm::FIELD_ZIP_CODE => $address->getZipCode(),
            AddressForm::FIELD_PO_BOX => $address->getPoBox(),
            AddressForm::FIELD_PHONE => $address->getPhone(),
            AddressForm::FIELD_CELL_PHONE => $address->getCellPhone(),
            AddressForm::FIELD_DESCRIPTION => $address->getDescription(),
            AddressForm::FIELD_COMMENT => $address->getComment(),
            AddressForm::FIELD_SALUTATION => $address->getSalutation(),
            AddressForm::FIELD_FK_COUNTRY => $address->getFkCountry(),
            AddressForm::FIELD_VAT_NUMBER => $address->getVatNumber(),
            AddressForm::FIELD_ADDRESS_4 => $address->getAddress4(),
            AddressForm::FIELD_STATE => $address->getState(),
        ];
    }

    /**
     * @return array
     */
    protected function createCountryOptionList()
    {
        $countryCollectionTransfer = new CountryCollectionTransfer();
        foreach ($this->store->getCountries() as $iso2Code) {
            $countryCollectionTransfer->addCountries((new CountryTransfer())->setIso2Code($iso2Code));
        }
        $countryCollectionTransfer = $this->countryFacade->findCountriesByIso2Codes($countryCollectionTransfer);

        $countryList = [];
        foreach ($countryCollectionTransfer->getCountries() as $countryTransfer) {
            $countryList[$countryTransfer->getIdCountry()] = $countryTransfer->getName();
        }

        return $countryList;
    }
}
