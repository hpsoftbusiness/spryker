<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Country\Business;

use Generated\Shared\Transfer\CountryTransfer;
use Spryker\Zed\Country\Business\CountryFacade as SprykerCountryFacade;

/**
 * @method \Spryker\Zed\Country\Business\CountryBusinessFactory getFactory()
 * @method \Pyz\Zed\Country\Persistence\CountryRepositoryInterface getRepository()
 */
class CountryFacade extends SprykerCountryFacade implements CountryFacadeInterface
{
    /**
     * @param string $countryName
     *
     * @return \Generated\Shared\Transfer\CountryTransfer|null
     */
    public function getCountryByName(string $countryName): ?CountryTransfer
    {
        return $this->getRepository()->getCountryByName($countryName);
    }
}
