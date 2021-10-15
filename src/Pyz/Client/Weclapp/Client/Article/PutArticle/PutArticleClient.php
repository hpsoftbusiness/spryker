<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\Weclapp\Client\Article\PutArticle;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\WeclappArticleTransfer;
use Pyz\Client\Weclapp\Client\Article\AbstractWeclappArticleClient;

class PutArticleClient extends AbstractWeclappArticleClient implements PutArticleClientInterface
{
    protected const REQUEST_METHOD = 'PUT';
    protected const ACTION_URL = '/article/id/%s';

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productTransfer
     * @param \Generated\Shared\Transfer\WeclappArticleTransfer $weclappArticleTransfer
     *
     * @return void
     */
    public function putArticle(
        ProductConcreteTransfer $productTransfer,
        WeclappArticleTransfer $weclappArticleTransfer
    ): void {
        $this->callWeclapp(
            static::REQUEST_METHOD,
            $this->getActionUrl($weclappArticleTransfer),
            $this->getRequestBody($productTransfer, $weclappArticleTransfer)
        );
    }

    /**
     * @param \Generated\Shared\Transfer\WeclappArticleTransfer $weclappArticleTransfer
     *
     * @return string
     */
    protected function getActionUrl(WeclappArticleTransfer $weclappArticleTransfer): string
    {
        return sprintf(static::ACTION_URL, $weclappArticleTransfer->getId());
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productTransfer
     * @param \Generated\Shared\Transfer\WeclappArticleTransfer $weclappArticleTransfer
     *
     * @return array
     */
    protected function getRequestBody(
        ProductConcreteTransfer $productTransfer,
        WeclappArticleTransfer $weclappArticleTransfer
    ): array {
        return $this->articleHydrator
            ->hydrateProductToWeclappArticle($productTransfer, $weclappArticleTransfer)
            ->toArray(true, true);
    }
}
