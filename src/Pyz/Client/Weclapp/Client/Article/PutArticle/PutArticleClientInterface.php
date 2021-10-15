<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\Weclapp\Client\Article\PutArticle;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\WeclappArticleTransfer;

interface PutArticleClientInterface
{
    /**
     * Put customer
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productTransfer
     * @param \Generated\Shared\Transfer\WeclappArticleTransfer $weclappArticleTransfer
     *
     * @return void
     */
    public function putArticle(
        ProductConcreteTransfer $productTransfer,
        WeclappArticleTransfer $weclappArticleTransfer
    ): void;
}
