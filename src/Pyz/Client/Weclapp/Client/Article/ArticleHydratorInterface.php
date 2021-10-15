<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\Weclapp\Client\Article;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\WeclappArticleTransfer;

interface ArticleHydratorInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productTransfer
     * @param \Generated\Shared\Transfer\WeclappArticleTransfer|null $weclappArticleTransfer
     *
     * @return \Generated\Shared\Transfer\WeclappArticleTransfer
     */
    public function hydrateProductToWeclappArticle(
        ProductConcreteTransfer $productTransfer,
        ?WeclappArticleTransfer $weclappArticleTransfer = null
    ): WeclappArticleTransfer;

    /**
     * @param array $weclappArticleData
     *
     * @return \Generated\Shared\Transfer\WeclappArticleTransfer
     */
    public function mapWeclappDataToWeclappArticle(array $weclappArticleData): WeclappArticleTransfer;
}
