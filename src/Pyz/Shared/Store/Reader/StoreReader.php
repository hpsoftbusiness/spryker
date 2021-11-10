<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Shared\Store\Reader;

use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Shared\Store\Reader\StoreReader as SprykerStoreReader;

class StoreReader extends SprykerStoreReader
{
    /**
     * @param string $storeName
     *
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    public function getStoreByName($storeName)
    {
        $storeTransfer = (new StoreTransfer())
            ->setName($storeName)
            ->setQueuePools($this->store->getQueuePools())
            ->setSelectedCurrencyIsoCode($this->store->getCurrentStoreSelectedCurrencyIsoCode())
            ->setDefaultCurrencyIsoCode($this->store->getDefaultCurrencyFor($storeName))
            ->setAvailableCurrencyIsoCodes($this->store->getAvailableCurrenciesFor($storeName))
            ->setAvailableLocaleIsoCodes($this->store->getCurrentAvailableLocaleIsoCodes())
            ->setStoresWithSharedPersistence($this->store->getStoresWithSharedPersistence())
            ->setCountries($this->store->getCountries())
            ->setTimezone($this->store->getTimezone());

        return $storeTransfer;
    }
}
