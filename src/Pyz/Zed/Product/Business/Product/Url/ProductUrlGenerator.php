<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Product\Business\Product\Url;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Spryker\Zed\Product\Business\Product\Url\ProductUrlGenerator as SprykerProductUrlGenerator;

class ProductUrlGenerator extends SprykerProductUrlGenerator
{
    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return string
     */
    protected function generateUrlByLocale(
        ProductAbstractTransfer $productAbstractTransfer,
        LocaleTransfer $localeTransfer
    ) {
        $productName = $this->utilTextService->generateSlug(
            $this->productAbstractNameGenerator->getLocalizedProductAbstractName(
                $productAbstractTransfer,
                $localeTransfer
            )
        );
        $languageIdentifier = mb_substr($localeTransfer->getLocaleName(), 0, 2);

        return '/' . $languageIdentifier . '/' . $productName . '-' . mb_strtolower($productAbstractTransfer->getSku());
    }
}
