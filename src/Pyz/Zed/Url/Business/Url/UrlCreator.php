<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Url\Business\Url;

use Generated\Shared\Transfer\UrlTransfer;
use Spryker\Zed\Url\Business\Url\UrlCreator as SprykerUrlCreator;

class UrlCreator extends SprykerUrlCreator
{
    /**
     * Override for turn off assertUrlDoesNotExist
     *
     * @param \Generated\Shared\Transfer\UrlTransfer $urlTransfer
     *
     * @return void
     */
    protected function assertUrlTransferForCreate(UrlTransfer $urlTransfer)
    {
        $urlTransfer
            ->requireUrl()
            ->requireFkLocale();
    }
}
