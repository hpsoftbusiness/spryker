<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\Weclapp\Client\ArticleCategory;

use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\WeclappArticleCategoryTransfer;

class ArticleCategoryHydrator implements ArticleCategoryHydratorInterface
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
    ): WeclappArticleCategoryTransfer {
        if (!$weclappArticleCategoryTransfer) {
            $weclappArticleCategoryTransfer = new WeclappArticleCategoryTransfer();
        }

        $weclappArticleCategoryTransfer->setName($categoryTransfer->getCategoryKeyOrFail())
            ->setDescription(
                $this->mapCategoryToWeclappDescription($categoryTransfer)
                ?? $weclappArticleCategoryTransfer->getDescription()
            )
            ->setParentCategoryId(
                $parentWeclappArticleCategoryTransfer
                    ? $parentWeclappArticleCategoryTransfer->getId()
                    : $weclappArticleCategoryTransfer->getParentCategoryId()
            );

        return $weclappArticleCategoryTransfer;
    }

    /**
     * @param array $weclappArticleCategoryData
     *
     * @return \Generated\Shared\Transfer\WeclappArticleCategoryTransfer
     */
    public function mapWeclappDataToWeclappArticleCategory(array $weclappArticleCategoryData): WeclappArticleCategoryTransfer
    {
        return (new WeclappArticleCategoryTransfer())->fromArray($weclappArticleCategoryData, true);
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return string|null
     */
    protected function mapCategoryToWeclappDescription(CategoryTransfer $categoryTransfer): ?string
    {
        $localizedAttribute = $categoryTransfer->getLocalizedAttributes()[0] ?? null;
        if (!$localizedAttribute) {
            return null;
        }

        return $localizedAttribute->getName();
    }
}
