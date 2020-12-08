<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\CustomerPage\CustomerAddress;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CountryCollectionTransfer;
use Generated\Shared\Transfer\CountryTransfer;
use Spryker\Client\Country\CountryClientInterface;
use SprykerShop\Yves\CustomerPage\CustomerAddress\AddressChoicesResolver as SprykerShopAddressChoicesResolver;

class AddressChoicesResolver extends SprykerShopAddressChoicesResolver
{
    protected const ADDRESS_LABEL_PATTERN = '%s %s %s, %s %s,%s %s %s, %s%s%s';

    /**
     * @var \Spryker\Client\Country\CountryClientInterface
     */
    protected $countryClient;

    /**
     * @param \Spryker\Client\Country\CountryClientInterface $countryClient
     */
    public function __construct(CountryClientInterface $countryClient)
    {
        $this->countryClient = $countryClient;
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     *
     * @return string
     */
    protected function getAddressLabel(AddressTransfer $addressTransfer): string
    {
        $countryName = $this->findCountryName($addressTransfer);

        return sprintf(
            static::ADDRESS_LABEL_PATTERN,
            $addressTransfer->getSalutation(),
            $addressTransfer->getFirstName(),
            $addressTransfer->getLastName(),
            $addressTransfer->getAddress1(),
            $addressTransfer->getAddress2(),
            $addressTransfer->getAddress3() ? $addressTransfer->getAddress3() . ', ' : null,
            $addressTransfer->getZipCode(),
            $addressTransfer->getCity(),
            $addressTransfer->getState() ? $addressTransfer->getState() . ', ' : null,
            $countryName ? $countryName . ', ' : null,
            $addressTransfer->getPhone()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     *
     * @return string|null
     */
    protected function findCountryName(AddressTransfer $addressTransfer): ?string
    {
        if ($addressTransfer->getCountry()) {
            return $addressTransfer->getCountry()->getName();
        }

        $countryTransfer = (new CountryTransfer())
            ->setIso2Code($addressTransfer->getIso2Code());
        $countryCollectionTransfer = (new CountryCollectionTransfer())
            ->addCountries($countryTransfer);
        $countryCollectionTransfer = $this->countryClient
            ->findCountriesByIso2Codes($countryCollectionTransfer);

        if ($countryCollectionTransfer->getCountries()->count()) {
            return null;
        }

        return $countryCollectionTransfer->getCountries()->offsetGet(0)->getName();
    }
}
