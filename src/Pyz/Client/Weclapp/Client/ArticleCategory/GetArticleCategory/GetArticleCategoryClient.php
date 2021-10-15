<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\Weclapp\Client\ArticleCategory\GetArticleCategory;

use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\WeclappArticleCategoryTransfer;
use Pyz\Client\Weclapp\Client\ArticleCategory\AbstractWeclappArticleCategoryClient;

class GetArticleCategoryClient extends AbstractWeclappArticleCategoryClient implements GetArticleCategoryClientInterface
{
    protected const REQUEST_METHOD = 'GET';
    protected const ACTION_URL = '/articleCategory?name-eq=%s';

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return \Generated\Shared\Transfer\WeclappArticleCategoryTransfer|null
     */
    public function getArticleCategory(CategoryTransfer $categoryTransfer): ?WeclappArticleCategoryTransfer
    {
        $weclappResponse = $this->callWeclapp(
            static::REQUEST_METHOD,
            $this->getActionUrl($categoryTransfer)
        );
        $weclappArticleCategoryData = json_decode($weclappResponse->getBody()->__toString(), true)['result'][0]
            ?? null;
        if (!$weclappArticleCategoryData) {
            return null;
        }

        return $this->articleCategoryHydrator->mapWeclappDataToWeclappArticleCategory($weclappArticleCategoryData);
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return string
     */
    protected function getActionUrl(CategoryTransfer $categoryTransfer): string
    {
        return sprintf(static::ACTION_URL, $categoryTransfer->getCategoryKey());
    }
}
