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
    protected const CRUTCH_LANGUAGE_IDENTIFIER = 'at';
    protected const CRUTCH_LOCALE = 'de_AT';

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

        if ($localeTransfer->getLocaleName() === self::CRUTCH_LOCALE) {
            $languageIdentifier = self::CRUTCH_LANGUAGE_IDENTIFIER;
        }

        return '/' . $languageIdentifier . '/' . $productName . '-' . $productAbstractTransfer->getIdProductAbstract();
    }
}
