<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Url\Business\Url;

use Generated\Shared\Transfer\UrlTransfer;
use Spryker\Zed\Url\Business\Url\UrlUpdater as SprykerUrlUpdater;

class UrlUpdater extends SprykerUrlUpdater
{
    /**
     * @param \Generated\Shared\Transfer\UrlTransfer $urlTransfer
     *
     * @return \Generated\Shared\Transfer\UrlTransfer
     */
    protected function executeUpdateUrlTransaction(UrlTransfer $urlTransfer): UrlTransfer
    {
        $urlEntity = $this->getUrlById($urlTransfer->getIdUrl());
        $originalUrlTransfer = new UrlTransfer();
        $originalUrlTransfer->fromArray($urlEntity->toArray(), true);

        $urlEntity->fromArray($urlTransfer->modifiedToArray());

        $this->notifyBeforeSaveObservers($urlTransfer, $originalUrlTransfer);

        $urlEntity->save();
        $this->urlActivator->activateUrl($urlTransfer);
        $this->notifyAfterSaveObservers($urlTransfer, $originalUrlTransfer);

        return $urlTransfer;
    }
}
