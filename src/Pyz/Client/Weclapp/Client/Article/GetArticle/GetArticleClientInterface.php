<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\Weclapp\Client\Article\GetArticle;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\WeclappArticleTransfer;

interface GetArticleClientInterface
{
    /**
     * Get article
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productTransfer
     *
     * @return \Generated\Shared\Transfer\WeclappArticleTransfer|null
     */
    public function getArticle(ProductConcreteTransfer $productTransfer): ?WeclappArticleTransfer;
}
