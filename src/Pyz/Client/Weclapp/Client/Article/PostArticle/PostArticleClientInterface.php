<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\Weclapp\Client\Article\PostArticle;

use Generated\Shared\Transfer\ProductConcreteTransfer;

interface PostArticleClientInterface
{
    /**
     * Post article
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productTransfer
     *
     * @return void
     */
    public function postArticle(ProductConcreteTransfer $productTransfer): void;
}
