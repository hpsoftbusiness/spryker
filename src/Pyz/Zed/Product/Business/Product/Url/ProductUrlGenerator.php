<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Product\Business\Product\Url;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\LocalizedUrlTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductUrlTransfer;
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

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductUrlTransfer
     */
    public function generateProductUrl(ProductAbstractTransfer $productAbstractTransfer)
    {
        $availableLocales = $this->localeFacade->getLocaleCollection();

        $productUrlTransfer = new ProductUrlTransfer();
        $productUrlTransfer->setAbstractSku($productAbstractTransfer->getSku());

        foreach ($availableLocales as $localeTransfer) {
            foreach ($productAbstractTransfer->getLocalizedAttributes() as $localizedAttribute) {
                if ($localeTransfer->getLocaleName() === $localizedAttribute->getLocale()->getLocaleName() &&
                    $localizedAttribute->getName() !== $productAbstractTransfer->getSku()) {
                    $url = $this->generateUrlByLocale($productAbstractTransfer, $localeTransfer);

                    $localizedUrl = new LocalizedUrlTransfer();
                    $localizedUrl->setLocale($localeTransfer);
                    $localizedUrl->setUrl($url);

                    $productUrlTransfer->addUrl($localizedUrl);
                }
            }
        }

        return $productUrlTransfer;
    }
}
