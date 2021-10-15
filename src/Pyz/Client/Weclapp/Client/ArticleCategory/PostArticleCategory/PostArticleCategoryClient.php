<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\Weclapp\Client\ArticleCategory\PostArticleCategory;

use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\WeclappArticleCategoryTransfer;
use Pyz\Client\Weclapp\Client\ArticleCategory\AbstractWeclappArticleCategoryClient;

class PostArticleCategoryClient extends AbstractWeclappArticleCategoryClient implements PostArticleCategoryClientInterface
{
    protected const REQUEST_METHOD = 'POST';
    protected const ACTION_URL = '/articleCategory';

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     * @param \Generated\Shared\Transfer\WeclappArticleCategoryTransfer|null $parentWeclappArticleCategoryTransfer
     *
     * @return \Generated\Shared\Transfer\WeclappArticleCategoryTransfer
     */
    public function postArticleCategory(
        CategoryTransfer $categoryTransfer,
        ?WeclappArticleCategoryTransfer $parentWeclappArticleCategoryTransfer
    ): WeclappArticleCategoryTransfer {
        $weclappResponse = $this->callWeclapp(
            static::REQUEST_METHOD,
            static::ACTION_URL,
            $this->getRequestBody($categoryTransfer, $parentWeclappArticleCategoryTransfer)
        );
        $weclappArticleCategoryData = json_decode($weclappResponse->getBody()->__toString(), true);

        return $this->articleCategoryHydrator
            ->mapWeclappDataToWeclappArticleCategory($weclappArticleCategoryData);
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     * @param \Generated\Shared\Transfer\WeclappArticleCategoryTransfer|null $parentWeclappArticleCategoryTransfer
     *
     * @return array
     */
    protected function getRequestBody(
        CategoryTransfer $categoryTransfer,
        ?WeclappArticleCategoryTransfer $parentWeclappArticleCategoryTransfer
    ): array {
        return $this->articleCategoryHydrator
            ->hydrateCategoryToWeclappArticleCategory($categoryTransfer, $parentWeclappArticleCategoryTransfer)
            ->toArray(true, true);
    }
}
