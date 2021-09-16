<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\UrlStorage\Storage;

use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Spryker\Client\UrlStorage\Storage\UrlStorageReader as SprykerUrlStorageReader;
use Spryker\Shared\Kernel\Store;

class UrlStorageReader extends SprykerUrlStorageReader
{
    /**
     * @param string $url
     *
     * @return string
     */
    protected function getUrlKey($url)
    {
        $synchronizationDataTransfer = new SynchronizationDataTransfer();
        $synchronizationDataTransfer->setReference(rawurldecode($url));
        $locale = Store::getInstance()->getCurrentLocale();
        $synchronizationDataTransfer->setLocale($locale);

        return $this->getStorageKeyBuilder()->generateKey($synchronizationDataTransfer);
    }
}
