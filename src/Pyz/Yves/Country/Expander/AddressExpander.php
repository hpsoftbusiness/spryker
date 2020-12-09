<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\Country\Expander;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CountryCollectionTransfer;
use Generated\Shared\Transfer\CountryTransfer;
use Spryker\Client\Country\CountryClientInterface;

class AddressExpander implements AddressExpanderInterface
{
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
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    public function expandAddressWithCountry(AddressTransfer $addressTransfer): AddressTransfer
    {
        if (!$addressTransfer->getIso2Code()) {
            return $addressTransfer;
        }

        if ($addressTransfer->getCountry()
            && $addressTransfer->getCountry()->getIso2Code() === $addressTransfer->getIso2Code()
        ) {
            return $addressTransfer;
        }

        $countryTransfer = $this->findCountryByIsoCode($addressTransfer->getIso2Code());

        if (!$countryTransfer) {
            return $addressTransfer;
        }

        return $addressTransfer->setCountry($countryTransfer);
    }

    /**
     * @param string $iso2Code
     *
     * @return \Generated\Shared\Transfer\CountryTransfer|null
     */
    protected function findCountryByIsoCode(string $iso2Code): ?CountryTransfer
    {
        $countryTransfer = (new CountryTransfer())
            ->setIso2Code($iso2Code);
        $countryCollectionTransfer = (new CountryCollectionTransfer())
            ->addCountries($countryTransfer);
        $countryCollectionTransfer = $this->countryClient
            ->findCountriesByIso2Codes($countryCollectionTransfer);

        if (!$countryCollectionTransfer->getCountries()->count()) {
            return null;
        }

        return $countryCollectionTransfer->getCountries()[0];
    }
}
