<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\UserStore\Business\UserExpander;

use Generated\Shared\Transfer\UserTransfer;
use Spryker\Zed\Store\Business\StoreFacadeInterface;

class UserExpander
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
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    public function expandUserTransferWithStore(UserTransfer $userTransfer): UserTransfer
    {
        $idStore = $userTransfer->getFkStore();
        $storeName = $userTransfer->getStoreName();

        if (!$idStore && $storeName) {
            $storeTransfer = $this->storeFacade->getStoreByName($storeName);
            $userTransfer->setFkStore($storeTransfer->getIdStore());
        } elseif ($idStore && !$storeName) {
            $storeTransfer = $this->storeFacade->getStoreById($idStore);
            $userTransfer->setStoreName($storeTransfer->getName());
        }

        return $userTransfer;
    }
}
