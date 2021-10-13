<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Country\Business;

use Generated\Shared\Transfer\CountryCollectionTransfer;
use Generated\Shared\Transfer\CountryTransfer;
use Pyz\Zed\Country\CountryConfig;
use Spryker\Zed\Country\Business\CountryManager as SprykerCountryManager;
use Spryker\Zed\Country\Persistence\CountryQueryContainerInterface;

class CountryManager extends SprykerCountryManager
{
    /**
     * @var \Pyz\Zed\Country\CountryConfig
     */
    private $config;

    /**
     * @param \Spryker\Zed\Country\Persistence\CountryQueryContainerInterface $countryQueryContainer
     * @param \Pyz\Zed\Country\CountryConfig $config
     */
    public function __construct(CountryQueryContainerInterface $countryQueryContainer, CountryConfig $config)
    {
        parent::__construct($countryQueryContainer);

        $this->config = $config;
    }

    /**
     * @return \Generated\Shared\Transfer\CountryCollectionTransfer
     */
    public function getCountryCollection(): CountryCollectionTransfer
    {
        $clusterCountriesIsoCodes = $this->config->getAvailableCountiesIso2Code();
        $countriesQuery = $this->countryQueryContainer->queryCountries()->orderByName();

        if (!empty($clusterCountriesIsoCodes)) {
            $countriesQuery->filterByIso2Code_In($clusterCountriesIsoCodes);
        }

        $countries = $countriesQuery->find();

        $countryCollectionTransfer = new CountryCollectionTransfer();

        foreach ($countries as $country) {
            $countryTransfer = (new CountryTransfer())->fromArray($country->toArray(), true);
            $countryCollectionTransfer->addCountries($countryTransfer);
        }

        return $countryCollectionTransfer;
    }
}
