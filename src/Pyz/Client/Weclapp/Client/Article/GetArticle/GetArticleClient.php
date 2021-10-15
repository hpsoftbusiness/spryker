<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\Weclapp\Client\Article\GetArticle;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\WeclappArticleTransfer;
use Pyz\Client\Weclapp\Client\Article\AbstractWeclappArticleClient;

class GetArticleClient extends AbstractWeclappArticleClient implements GetArticleClientInterface
{
    protected const REQUEST_METHOD = 'GET';
    protected const ACTION_URL = '/article?articleNumber-eq=%s';

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productTransfer
     *
     * @return \Generated\Shared\Transfer\WeclappArticleTransfer|null
     */
    public function getArticle(ProductConcreteTransfer $productTransfer): ?WeclappArticleTransfer
    {
        $weclappResponse = $this->callWeclapp(
            static::REQUEST_METHOD,
            $this->getActionUrl($productTransfer)
        );
        $weclappArticleData = json_decode($weclappResponse->getBody()->__toString(), true)['result'][0]
            ?? null;
        if (!$weclappArticleData) {
            return null;
        }

        return $this->articleHydrator->mapWeclappDataToWeclappArticle($weclappArticleData);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productTransfer
     *
     * @return string
     */
    protected function getActionUrl(ProductConcreteTransfer $productTransfer): string
    {
        return sprintf(static::ACTION_URL, $productTransfer->getSkuOrFail());
    }
}
