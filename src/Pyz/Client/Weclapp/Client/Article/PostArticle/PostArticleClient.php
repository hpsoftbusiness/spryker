<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\Weclapp\Client\Article\PostArticle;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use Pyz\Client\Weclapp\Client\Article\AbstractWeclappArticleClient;

class PostArticleClient extends AbstractWeclappArticleClient implements PostArticleClientInterface
{
    protected const REQUEST_METHOD = 'POST';
    protected const ACTION_URL = '/article';

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productTransfer
     *
     * @return void
     */
    public function postArticle(ProductConcreteTransfer $productTransfer): void
    {
        $this->callWeclapp(
            static::REQUEST_METHOD,
            static::ACTION_URL,
            $this->getRequestBody($productTransfer)
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productTransfer
     *
     * @return array
     */
    protected function getRequestBody(ProductConcreteTransfer $productTransfer): array
    {
        return $this->articleHydrator
            ->hydrateProductToWeclappArticle($productTransfer)
            ->toArray(true, true);
    }
}
