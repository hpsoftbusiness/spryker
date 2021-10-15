<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Country\Persistence;

use Generated\Shared\Transfer\CountryTransfer;
use Spryker\Zed\Country\Persistence\CountryRepositoryInterface as SprykerCountryRepositoryInterface;

interface CountryRepositoryInterface extends SprykerCountryRepositoryInterface
{
    /**
     * @param string $countryName
     *
     * @return \Generated\Shared\Transfer\CountryTransfer|null
     */
    public function getCountryByName(string $countryName): ?CountryTransfer;
}
