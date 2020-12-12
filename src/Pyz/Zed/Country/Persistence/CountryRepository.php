<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Country\Persistence;

use Generated\Shared\Transfer\CountryCollectionTransfer;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Country\Persistence\CountryRepository as SprykerCountryRepository;

/**
 * @method \Spryker\Zed\Country\Persistence\CountryPersistenceFactory getFactory()
 */
class CountryRepository extends SprykerCountryRepository
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
}
