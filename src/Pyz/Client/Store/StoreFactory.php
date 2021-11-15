<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\Store;

use Pyz\Shared\Store\Reader\StoreReader;
use Spryker\Client\Store\StoreFactory as SprykerStoreFactory;

class StoreFactory extends SprykerStoreFactory
{
    /**
     * @return \Spryker\Shared\Store\Reader\StoreReaderInterface
     */
    public function createStoreReader()
    {
        return new StoreReader($this->getStore());
    }
}
