<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\Weclapp\Client\ArticleCategory;

use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\WeclappArticleCategoryTransfer;

interface ArticleCategoryHydratorInterface
{
    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     * @param \Generated\Shared\Transfer\WeclappArticleCategoryTransfer|null $parentWeclappArticleCategoryTransfer
     * @param \Generated\Shared\Transfer\WeclappArticleCategoryTransfer|null $weclappArticleCategoryTransfer
     *
     * @return \Generated\Shared\Transfer\WeclappArticleCategoryTransfer
     */
    public function hydrateCategoryToWeclappArticleCategory(
        CategoryTransfer $categoryTransfer,
        ?WeclappArticleCategoryTransfer $parentWeclappArticleCategoryTransfer,
        ?WeclappArticleCategoryTransfer $weclappArticleCategoryTransfer = null
    ): WeclappArticleCategoryTransfer;

    /**
     * @param array $weclappArticleCategoryData
     *
     * @return \Generated\Shared\Transfer\WeclappArticleCategoryTransfer
     */
    public function mapWeclappDataToWeclappArticleCategory(array $weclappArticleCategoryData): WeclappArticleCategoryTransfer;
}
