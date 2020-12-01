<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Sales\Dependency\Facade;

use Generated\Shared\Transfer\CountryCollectionTransfer;
use Spryker\Zed\Sales\Dependency\Facade\SalesToCountryBridge as SprykerSalesToCountryBridge;

class SalesToCountryBridge extends SprykerSalesToCountryBridge implements SalesToCountryInterface
{
    /**
     * @param \Generated\Shared\Transfer\CountryCollectionTransfer $countryCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\CountryCollectionTransfer
     */
    public function findCountriesByIso2Codes(CountryCollectionTransfer $countryCollectionTransfer): CountryCollectionTransfer
    {
        return $this->countryFacade->findCountriesByIso2Codes($countryCollectionTransfer);
    }
}
