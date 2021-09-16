<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Shared\Kernel;

use Exception;
use Pyz\Shared\Locale\LocaleConstants;
use Spryker\Shared\Config\Config;
use Spryker\Shared\Kernel\Store as SprykerStore;

class Store extends SprykerStore
{
    /**
     * @param string $currentStoreName
     *
     * @throws \Exception
     *
     * @return void
     */
    public function initializeSetup($currentStoreName)
    {
        $stores = $this->getStoreSetup($currentStoreName);
        $storeArray = $stores[$currentStoreName];
        $storeArray['locales'] = $this->getLocalsByStore($currentStoreName);
        $vars = get_object_vars($this);
        foreach ($storeArray as $k => $v) {
            if (!array_key_exists($k, $vars)) {
                throw new Exception('Unknown setup-key: ' . $k);
            }
            $this->$k = $v;
        }

        $this->storeName = $currentStoreName;
        $this->allStoreNames = array_keys($stores);
        $this->allStores = $stores;

        $this->setCurrencyIsoCode($this->getDefaultCurrencyCode());
        $this->setCurrentLocale(current($this->locales));
        $this->setCurrentCountry(current($this->countries));
    }

    /**
     * @param string $store
     *
     * @return array
     */
    private function getLocalsByStore(string $store): array
    {
        $localsPerStores = Config::get(LocaleConstants::LOCALS_PER_STORES, []);

        return $localsPerStores[$store] ?? [];
    }
}
