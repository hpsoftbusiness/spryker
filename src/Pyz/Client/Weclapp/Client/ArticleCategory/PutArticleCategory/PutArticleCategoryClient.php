<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\Weclapp\Client\ArticleCategory\PutArticleCategory;

use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\WeclappArticleCategoryTransfer;
use Pyz\Client\Weclapp\Client\ArticleCategory\AbstractWeclappArticleCategoryClient;

class PutArticleCategoryClient extends AbstractWeclappArticleCategoryClient implements PutArticleCategoryClientInterface
{
    protected const REQUEST_METHOD = 'PUT';
    protected const ACTION_URL = '/articleCategory/id/%s';

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     * @param \Generated\Shared\Transfer\WeclappArticleCategoryTransfer $weclappArticleCategoryTransfer
     * @param \Generated\Shared\Transfer\WeclappArticleCategoryTransfer|null $parentWeclappArticleCategoryTransfer
     *
     * @return \Generated\Shared\Transfer\WeclappArticleCategoryTransfer
     */
    public function putArticleCategory(
        CategoryTransfer $categoryTransfer,
        WeclappArticleCategoryTransfer $weclappArticleCategoryTransfer,
        ?WeclappArticleCategoryTransfer $parentWeclappArticleCategoryTransfer
    ): WeclappArticleCategoryTransfer {
        $weclappResponse = $this->callWeclapp(
            static::REQUEST_METHOD,
            $this->getActionUrl($weclappArticleCategoryTransfer),
            $this->getRequestBody(
                $categoryTransfer,
                $weclappArticleCategoryTransfer,
                $parentWeclappArticleCategoryTransfer
            )
        );
        $weclappArticleCategoryData = json_decode($weclappResponse->getBody()->__toString(), true);

        return $this->articleCategoryHydrator->mapWeclappDataToWeclappArticleCategory($weclappArticleCategoryData);
    }

    /**
     * @param \Generated\Shared\Transfer\WeclappArticleCategoryTransfer $weclappArticleCategoryTransfer
     *
     * @return string
     */
    protected function getActionUrl(WeclappArticleCategoryTransfer $weclappArticleCategoryTransfer): string
    {
        return sprintf(static::ACTION_URL, $weclappArticleCategoryTransfer->getId());
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     * @param \Generated\Shared\Transfer\WeclappArticleCategoryTransfer $weclappArticleCategoryTransfer
     * @param \Generated\Shared\Transfer\WeclappArticleCategoryTransfer|null $parentWeclappArticleCategoryTransfer
     *
     * @return array
     */
    protected function getRequestBody(
        CategoryTransfer $categoryTransfer,
        WeclappArticleCategoryTransfer $weclappArticleCategoryTransfer,
        ?WeclappArticleCategoryTransfer $parentWeclappArticleCategoryTransfer
    ): array {
        return $this->articleCategoryHydrator
            ->hydrateCategoryToWeclappArticleCategory(
                $categoryTransfer,
                $parentWeclappArticleCategoryTransfer,
                $weclappArticleCategoryTransfer,
            )
            ->toArray(true, true);
    }
}
