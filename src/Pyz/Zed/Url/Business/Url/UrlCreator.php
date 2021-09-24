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
     * @var \Pyz\Zed\Url\Persistence\UrlQueryContainerInterface
     */
    protected $urlQueryContainer;

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

    /**
     * @param \Generated\Shared\Transfer\UrlTransfer $urlTransfer
     *
     * @return \Generated\Shared\Transfer\UrlTransfer
     */
    protected function persistUrlEntity(UrlTransfer $urlTransfer)
    {
        $urlTransfer->requireUrl();
        $urlEntity = $this->urlQueryContainer->queryLocaleUrl(
            $urlTransfer->getUrl(),
            $urlTransfer->getFkLocale()
        )->findOneOrCreate();

        $urlEntity->fromArray($urlTransfer->modifiedToArray());
        $urlEntity->save();

        $urlTransfer->fromArray($urlEntity->toArray(), true);

        return $urlTransfer;
    }
}
