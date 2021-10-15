<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Country\Persistence;

use Generated\Shared\Transfer\CountryCollectionTransfer;
use Generated\Shared\Transfer\CountryTransfer;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Country\Persistence\CountryRepository as SprykerCountryRepository;

/**
 * @method \Spryker\Zed\Country\Persistence\CountryPersistenceFactory getFactory()
 */
class CountryRepository extends SprykerCountryRepository implements CountryRepositoryInterface
{
    /**
     * @param string[] $iso2Codes
     *
     * @return \Generated\Shared\Transfer\CountryCollectionTransfer
     */
    public function findCountriesByIso2Codes(array $iso2Codes): CountryCollectionTransfer
    {
        $countryQuery = $this->getFactory()
            ->createCountryQuery()
            ->joinWithSpyRegion(Criteria::LEFT_JOIN)
            ->filterByIso2Code_In($iso2Codes)
            ->orderByName();
        $countries = $this->buildQueryFromCriteria($countryQuery)->find();

        return $this->getFactory()
            ->createCountryMapper()
            ->mapCountryTransferCollection($countries);
    }

    /**
     * @param string $countryName
     *
     * @return \Generated\Shared\Transfer\CountryTransfer|null
     */
    public function getCountryByName(string $countryName): ?CountryTransfer
    {
        $countryQuery = $this->getFactory()
            ->createCountryQuery()
            ->filterByName($countryName);
        $countryEntityTransfer = $this->buildQueryFromCriteria($countryQuery)->findOne();

        if ($countryEntityTransfer === null) {
            return null;
        }

        return $this->getFactory()
            ->createCountryMapper()
            ->mapCountryTransfer($countryEntityTransfer);
    }
}
