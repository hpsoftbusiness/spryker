<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\Weclapp\Client\ArticleCategory\GetArticleCategory;

use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\WeclappArticleCategoryTransfer;

interface GetArticleCategoryClientInterface
{
    /**
     * Get article category
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return \Generated\Shared\Transfer\WeclappArticleCategoryTransfer|null
     */
    public function getArticleCategory(CategoryTransfer $categoryTransfer): ?WeclappArticleCategoryTransfer;
}
