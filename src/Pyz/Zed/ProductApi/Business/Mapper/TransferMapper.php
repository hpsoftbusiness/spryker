<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductApi\Business\Mapper;

use Generated\Shared\Transfer\CategoryCollectionTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\LocalizedAttributesTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductApiTransfer;
use Generated\Shared\Transfer\ProductBenefitApiTransfer;
use Generated\Shared\Transfer\ProductBvDealApiTransfer;
use Generated\Shared\Transfer\ProductImageSetTransfer;
use Generated\Shared\Transfer\ProductPriceApiTransfer;
use Generated\Shared\Transfer\ProductSpDealApiTransfer;
use Generated\Shared\Transfer\ProductsResponseApiTransfer;
use Generated\Shared\Transfer\ProductUrlTransfer;
use Pyz\Shared\MyWorldPayment\MyWorldPaymentConstants;
use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Shared\Config\Config;

class TransferMapper implements TransferMapperInterface
{
    /**
     * @param array $productEntityCollection
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param string $title
     *
     * @return \Generated\Shared\Transfer\ProductsResponseApiTransfer
     */
    public function toTransferCollection(
        array $productEntityCollection,
        LocaleTransfer $localeTransfer,
        string $title
    ): ProductsResponseApiTransfer {
        $responseTransfer = new ProductsResponseApiTransfer();
        $responseTransfer->setTitle($title);
        $responseTransfer->setLanguage($localeTransfer->getLocaleName());
        foreach ($productEntityCollection as $productEntity) {
            $productTransfer = new ProductApiTransfer();
            $productTransfer->setProductId($productEntity['IdProductAbstract']);

            $responseTransfer->addProduct($productTransfer);
        }

        return $responseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     * @param \Generated\Shared\Transfer\ProductUrlTransfer $productUrlTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param \Generated\Shared\Transfer\CategoryCollectionTransfer $productCategoryTransferCollection
     *
     * @return \Generated\Shared\Transfer\ProductApiTransfer
     */
    public function toTransfer(
        ProductAbstractTransfer $productAbstractTransfer,
        ProductUrlTransfer $productUrlTransfer,
        LocaleTransfer $localeTransfer,
        CategoryCollectionTransfer $productCategoryTransferCollection
    ): ProductApiTransfer {
        $localizedAttributes = $this->getLocalizedAttributes($productAbstractTransfer, $localeTransfer);

        $productApiTransfer = new ProductApiTransfer();
        $productApiTransfer->setProductId($productAbstractTransfer->getIdProductAbstract())
            ->setProductName($localizedAttributes->getName())
            ->setDescription($localizedAttributes->getDescription())
            ->setCategory($this->getCategory($productCategoryTransferCollection, $localeTransfer))
            ->setImageUrl($this->getImageUrl($productAbstractTransfer, $localeTransfer))
            ->setProductUrl($this->getProductUrl($productUrlTransfer, $localeTransfer))
            ->setBenefit($this->getBenefitApi($productAbstractTransfer))
            ->setPrice($this->getPriceApi($productAbstractTransfer))
            ->setBvDeal($this->getBvDealApi($productAbstractTransfer))
            ->setSpDeal($this->getSpDealApi($productAbstractTransfer));

        return $productApiTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Generated\Shared\Transfer\LocalizedAttributesTransfer
     */
    protected function getLocalizedAttributes(
        ProductAbstractTransfer $productAbstractTransfer,
        LocaleTransfer $localeTransfer
    ): LocalizedAttributesTransfer {
        foreach ($productAbstractTransfer->getLocalizedAttributes() as $localizedAttributes) {
            if ($localizedAttributes->getLocale()->getIdLocale() === $localeTransfer->getIdLocale()) {
                return $localizedAttributes;
            }
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return string|null
     */
    protected function getImageUrl(
        ProductAbstractTransfer $productAbstractTransfer,
        LocaleTransfer $localeTransfer
    ): ?string {
        $defaultImageUrl = null;
        foreach ($productAbstractTransfer->getImageSets() as $imageSet) {
            if ($imageSet->getLocale()) {
                if ($imageSet->getLocale()->getIdLocale() === $localeTransfer->getIdLocale()) {
                    return $this->getImageUrlFromImageSet($imageSet);
                }
            } else {
                $defaultImageUrl = $this->getImageUrlFromImageSet($imageSet);
            }
        }

        return $defaultImageUrl;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductImageSetTransfer $imageSetTransfer
     *
     * @return string|null
     */
    protected function getImageUrlFromImageSet(ProductImageSetTransfer $imageSetTransfer): ?string
    {
        return isset($imageSetTransfer->getProductImages()[0])
            ? $imageSetTransfer->getProductImages()[0]->getExternalUrlLarge()
            : null;
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryCollectionTransfer $productCategoryTransferCollection
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return string|null
     */
    protected function getCategory(
        CategoryCollectionTransfer $productCategoryTransferCollection,
        LocaleTransfer $localeTransfer
    ): ?string {
        if (!isset($productCategoryTransferCollection->getCategories()[0])) {
            return null;
        }

        foreach ($productCategoryTransferCollection->getCategories()[0]->getLocalizedAttributes() as $localizedAttributes) {
            if ($localizedAttributes->getLocale()->getIdLocale() === $localeTransfer->getIdLocale()) {
                return $localizedAttributes->getName();
            }
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ProductUrlTransfer $productUrlTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return string
     */
    protected function getProductUrl(
        ProductUrlTransfer $productUrlTransfer,
        LocaleTransfer $localeTransfer
    ): string {
        foreach ($productUrlTransfer->getUrls() as $url) {
            if ($url->getLocale()->getIdLocale() === $localeTransfer->getIdLocale()) {
                return Config::get(ApplicationConstants::BASE_URL_YVES)
                    . $url->getUrl();
            }
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductBenefitApiTransfer
     */
    protected function getBenefitApi(ProductAbstractTransfer $productAbstractTransfer): ProductBenefitApiTransfer
    {
        $benefitApi = new ProductBenefitApiTransfer();
        $benefitApi->setCashbackAmount($this->formatAmount(
            $productAbstractTransfer->getAttributes()['cashback_amount'] ?? null
        ))
            ->setShoppingPointsAmount($this->formatAmount(
                $productAbstractTransfer->getAttributes()['shopping_points'] ?? null
            ));

        return $benefitApi;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductPriceApiTransfer
     */
    protected function getPriceApi(ProductAbstractTransfer $productAbstractTransfer): ProductPriceApiTransfer
    {
        $moneyValue = isset($productAbstractTransfer->getPrices()[0])
            ? $productAbstractTransfer->getPrices()[0]->getMoneyValue()
            : null;
        $productPriceApi = new ProductPriceApiTransfer();
        if ($moneyValue) {
            $productPriceApi->setAmount($this->formatAmount($moneyValue->getGrossAmount() / 100))
                ->setCurrency($moneyValue->getCurrency()->getCode());
        }

        return $productPriceApi;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductBvDealApiTransfer
     */
    protected function getBvDealApi(ProductAbstractTransfer $productAbstractTransfer): ProductBvDealApiTransfer
    {
        $productBcDealApi = new ProductBvDealApiTransfer();
        $benefitStoreSalesPriceAttrKey = Config::get(
            MyWorldPaymentConstants::PRODUCT_ATTRIBUTE_KEY_BENEFIT_STORE_SALES_PRICE
        );
        $benefitAmountAttrKey = Config::get(
            MyWorldPaymentConstants::PRODUCT_ATTRIBUTE_KEY_BENEFIT_AMOUNT
        );
        $productBcDealApi->setBvItemPrice($this->formatAmount(
            $productAbstractTransfer->getAttributes()[$benefitStoreSalesPriceAttrKey] ?? null
        ))
            ->setBvAmount($this->formatAmount(
                $productAbstractTransfer->getAttributes()[$benefitAmountAttrKey] ?? null
            ));

        return $productBcDealApi;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductSpDealApiTransfer
     */
    protected function getSpDealApi(ProductAbstractTransfer $productAbstractTransfer): ProductSpDealApiTransfer
    {
        $benefitStoreSalesPriceAttrKey = Config::get(
            MyWorldPaymentConstants::PRODUCT_ATTRIBUTE_KEY_BENEFIT_STORE_SALES_PRICE
        );

        $productSpDealApi = new ProductSpDealApiTransfer();
        $productSpDealApi->setSpItemPrice($this->formatAmount(
            $productAbstractTransfer->getAttributes()[$benefitStoreSalesPriceAttrKey] ?? null
        ))
            ->setSpAmount($this->formatAmount(
                $productAbstractTransfer->getAttributes()['shopping_points'] ?? null
            ));

        return $productSpDealApi;
    }

    /**
     * @param mixed $amount
     *
     * @return string|null
     */
    protected function formatAmount($amount): ?string
    {
        if ($amount === null) {
            return null;
        }

        return number_format((float)$amount, 2, '.', '');
    }
}
