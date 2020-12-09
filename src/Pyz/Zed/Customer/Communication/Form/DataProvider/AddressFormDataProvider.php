<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Customer\Communication\Form\DataProvider;

use Spryker\Zed\Customer\Communication\Form\DataProvider\AddressFormDataProvider as SprykerAddressFormDataProvider;

class AddressFormDataProvider extends SprykerAddressFormDataProvider
{
    /**
     * @return array
     */
    protected function getCountryChoices()
    {
        $result = [];
        foreach ($this->store->getCountries() as $iso2Code) {
            $countryTransfer = $this->countryFacade->getCountryByIso2Code($iso2Code);
            $result[$countryTransfer->getIdCountry()] = $countryTransfer->getName();
        }
        asort($result);

        return $result;
    }
}
