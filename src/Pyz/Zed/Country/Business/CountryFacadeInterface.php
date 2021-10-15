<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Country\Business;

use Generated\Shared\Transfer\CountryTransfer;
use Spryker\Zed\Country\Business\CountryFacadeInterface as SprykerCountryFacadeInterface;
use Spryker\Zed\Sales\Dependency\Facade\SalesToCountryInterface;

interface CountryFacadeInterface extends SprykerCountryFacadeInterface, SalesToCountryInterface
{
    /**
     * @param string $countryName
     *
     * @return \Generated\Shared\Transfer\CountryTransfer|null
     */
    public function getCountryByName(string $countryName): ?CountryTransfer;
}
