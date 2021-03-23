<?php

namespace Pyz\Zed\ProductApi\Business\Mapper;

use Generated\Shared\Transfer\CategoryCollectionTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\LocalizedAttributesTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductApiTransfer;
use Generated\Shared\Transfer\ProductBenefitApiTransfer;
use Generated\Shared\Transfer\ProductBvDealApiTransfer;
use Generated\Shared\Transfer\ProductPriceApiTransfer;
use Generated\Shared\Transfer\ProductSpDealApiTransfer;
use Generated\Shared\Transfer\ProductsResponseApiTransfer;
use Generated\Shared\Transfer\ProductUrlTransfer;
use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Shared\Config\Config;

class TransferMapper implements TransferMapperInterface
{
    /**
     * @param array $productEntityCollection
     * @param LocaleTransfer $localeTransfer,
     * @param string $title
     *
     * @return ProductsResponseApiTransfer
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
     * @param ProductAbstractTransfer $productAbstractTransfer
     * @param ProductUrlTransfer $productUrlTransfer
     * @param LocaleTransfer $localeTransfer
     * @param CategoryCollectionTransfer $productCategoryTransferCollection
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
     * @param ProductAbstractTransfer $productAbstractTransfer
     * @param LocaleTransfer $localeTransfer
     *
     * @return LocalizedAttributesTransfer
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
     * @param ProductAbstractTransfer $productAbstractTransfer
     * @param LocaleTransfer $localeTransfer
     *
     * @return string|null
     */
    protected function getImageUrl(
        ProductAbstractTransfer $productAbstractTransfer,
        LocaleTransfer $localeTransfer
    ): ?string {
        foreach ($productAbstractTransfer->getImageSets() as $imageSet) {
            if ($imageSet->getLocale()->getIdLocale() === $localeTransfer->getIdLocale()) {
                return isset($imageSet->getProductImages()[0])
                    ? $imageSet->getProductImages()[0]->getExternalUrlLarge()
                    : null;
            }
        }
    }

    /**
     * @param CategoryCollectionTransfer $productCategoryTransferCollection
     * @param LocaleTransfer $localeTransfer
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
     * @param ProductUrlTransfer $productUrlTransfer
     * @param LocaleTransfer $localeTransfer
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
     * @param ProductAbstractTransfer $productAbstractTransfer
     *
     * @return ProductBenefitApiTransfer
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
     * @param ProductAbstractTransfer $productAbstractTransfer
     *
     * @return ProductPriceApiTransfer
     */
    protected function getPriceApi(ProductAbstractTransfer $productAbstractTransfer): ProductPriceApiTransfer
    {
        $moneyValue = isset($productAbstractTransfer->getPrices()[0])
            ? $productAbstractTransfer->getPrices()[0]->getMoneyValue()
            : null;
        $productPriceApi = new ProductPriceApiTransfer();
        if ($moneyValue) {
            $productPriceApi->setAmount($this->formatAmount($moneyValue->getGrossAmount()))
                ->setCurrency($moneyValue->getCurrency()->getCode());
        }
        return $productPriceApi;
    }

    /**
     * @param ProductAbstractTransfer $productAbstractTransfer
     *
     * @return ProductBvDealApiTransfer
     */
    protected function getBvDealApi(ProductAbstractTransfer $productAbstractTransfer): ProductBvDealApiTransfer
    {
        $productBcDealApi = new ProductBvDealApiTransfer();
        $productBcDealApi->setBvItemPrice($this->formatAmount(
            $productAbstractTransfer->getAttributes()['Benefit_Store_sales_price'] ?? null
        ))
            ->setBvAmount($this->formatAmount(
                $productAbstractTransfer->getAttributes()['Benefit_amount'] ?? null
            ));
        return $productBcDealApi;
    }

    /**
     * @param ProductAbstractTransfer $productAbstractTransfer
     *
     * @return ProductSpDealApiTransfer
     */
    protected function getSpDealApi(ProductAbstractTransfer $productAbstractTransfer): ProductSpDealApiTransfer
    {
        $productSpDealApi = new ProductSpDealApiTransfer();
        $productSpDealApi->setSpItemPrice($this->formatAmount(
            $productAbstractTransfer->getAttributes()['Benefit_Store_sales_price'] ?? null
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
