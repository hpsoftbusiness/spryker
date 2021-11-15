<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\UserStore\Communication\FormExpander\DataProvider;

use Pyz\Zed\UserStore\Communication\FormExpander\UserStoreFormExpander;
use Spryker\Zed\Store\Business\StoreFacadeInterface;

class StoreChoiceFormDataProvider
{
    /**
     * @var \Spryker\Zed\Store\Business\StoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @param \Spryker\Zed\Store\Business\StoreFacadeInterface $storeFacade
     */
    public function __construct(StoreFacadeInterface $storeFacade)
    {
        $this->storeFacade = $storeFacade;
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return [
            UserStoreFormExpander::OPTIONS_STORE => $this->getStores(),
        ];
    }

    /**
     * @return array
     */
    protected function getStores(): array
    {
        $storeTransfers = $this->storeFacade->getAllStores();
        $options = [];

        foreach ($storeTransfers as $storeTransfer) {
            $options[$storeTransfer->getName()] = $storeTransfer->getIdStore();
        }

        return $options;
    }
}
