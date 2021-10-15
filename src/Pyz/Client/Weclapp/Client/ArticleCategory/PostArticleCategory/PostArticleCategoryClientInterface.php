<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\Weclapp\Client\ArticleCategory\PostArticleCategory;

use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\WeclappArticleCategoryTransfer;

interface PostArticleCategoryClientInterface
{
    /**
     * Post article category
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     * @param \Generated\Shared\Transfer\WeclappArticleCategoryTransfer|null $parentWeclappArticleCategoryTransfer
     *
     * @return \Generated\Shared\Transfer\WeclappArticleCategoryTransfer
     */
    public function postArticleCategory(
        CategoryTransfer $categoryTransfer,
        ?WeclappArticleCategoryTransfer $parentWeclappArticleCategoryTransfer
    ): WeclappArticleCategoryTransfer;
}
