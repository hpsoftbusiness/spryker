<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductApi\Business\Mapper;

use Generated\Shared\Transfer\CategoryCollectionTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\LocalizedAttributesTransfer;
use Generated\Shared\Transfer\MoneyValueTransfer;
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
use Pyz\Zed\PriceProduct\Business\PriceProductFacadeInterface;
use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Shared\Config\Config;

class TransferMapper implements TransferMapperInterface
{
    /**
     * @var \Pyz\Zed\PriceProduct\Business\PriceProductFacadeInterface
     */
    protected $priceProductFacade;

    /**
     * @param \Pyz\Zed\PriceProduct\Business\PriceProductFacadeInterface $priceProductFacade
     */
    public function __construct(PriceProductFacadeInterface $priceProductFacade)
    {
        $this->priceProductFacade = $priceProductFacade;
    }

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
            ->setOriginalGrossPrice($this->getOriginalGrossPriceApi($productAbstractTransfer))
            ->setBvDeal($this->getBvDealApi($productAbstractTransfer))
            ->setSpDeal($this->getSpDealApi($productAbstractTransfer))
            ->setMarketerOnly($this->getMarketerOnlyFlag($productAbstractTransfer))
            ->setCbwPrivateOnly($this->getCbwPrivateOnlyFlag($productAbstractTransfer));

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

        return new LocalizedAttributesTransfer();
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

        foreach ($productCategoryTransferCollection->getCategories()[0]->getLocalizedAttributes(
        ) as $localizedAttributes) {
            if ($localizedAttributes->getLocale()->getIdLocale() === $localeTransfer->getIdLocale()) {
                return $localizedAttributes->getName();
            }
        }

        return null;
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

        return Config::get(ApplicationConstants::BASE_URL_YVES);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductBenefitApiTransfer
     */
    protected function getBenefitApi(ProductAbstractTransfer $productAbstractTransfer): ProductBenefitApiTransfer
    {
        $benefitApi = new ProductBenefitApiTransfer();
        $benefitApi->setCashbackAmount(
            $this->formatAmount(
                $productAbstractTransfer->getAttributes()['cashback_amount'] ?? null
            )
        )
            ->setShoppingPointsAmount(
                $this->formatAmount(
                    $productAbstractTransfer->getAttributes()['shopping_points'] ?? null
                )
            );

        return $benefitApi;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductPriceApiTransfer
     */
    protected function getPriceApi(ProductAbstractTransfer $productAbstractTransfer): ProductPriceApiTransfer
    {
        $moneyValue = $this->getDefaultPrice($productAbstractTransfer);
        $productPriceApi = new ProductPriceApiTransfer();
        if ($moneyValue) {
            $productPriceApi
                ->setAmount(
                    $this->formatAmount($moneyValue->getGrossAmount() / 100)
                )
                ->setCurrency(
                    $moneyValue
                        ->getCurrency()
                        ->getCode()
                );
        }

        return $productPriceApi;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     * @param string $priceTypeName
     *
     * @return \Generated\Shared\Transfer\MoneyValueTransfer|null
     */
    protected function getMoneyValue(
        ProductAbstractTransfer $productAbstractTransfer,
        string $priceTypeName
    ): ?MoneyValueTransfer {
        foreach ($productAbstractTransfer->getPrices() as $priceProductTransfer) {
            if ($priceProductTransfer->getPriceTypeName() === $priceTypeName) {
                return $priceProductTransfer->getMoneyValue();
            }
        }

        return null;
    }

    /**
     * @param \Generated\Shared\Transfer\MoneyValueTransfer|null $moneyValueTransfer
     *
     * @return string|null
     */
    protected function getFormattedGrossAmountByMoneyValue(?MoneyValueTransfer $moneyValueTransfer): ?string
    {
        if ($moneyValueTransfer === null) {
            return null;
        }
        $grossAmount = $moneyValueTransfer->getGrossAmount();

        return $this->formatAmount($grossAmount !== null ? $grossAmount / 100 : null);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductPriceApiTransfer
     */
    protected function getOriginalGrossPriceApi(ProductAbstractTransfer $productAbstractTransfer): ProductPriceApiTransfer
    {
        $moneyValueTransfer = $this->getMoneyValue(
            $productAbstractTransfer,
            $this->priceProductFacade->getPriceTypeOriginalName()
        );
        $productPriceApiTransfer = new ProductPriceApiTransfer();
        $productPriceApiTransfer->setAmount($this->getFormattedGrossAmountByMoneyValue($moneyValueTransfer))
            ->setCurrency($moneyValueTransfer ? $moneyValueTransfer->getCurrency()->getCode() : null);

        return $productPriceApiTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\MoneyValueTransfer|null
     */
    protected function getBenefitPrice(ProductAbstractTransfer $productAbstractTransfer): ?MoneyValueTransfer
    {
        return $this->getMoneyValue(
            $productAbstractTransfer,
            $this->priceProductFacade->getBenefitPriceTypeName()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\MoneyValueTransfer|null
     */
    protected function getDefaultPrice(ProductAbstractTransfer $productAbstractTransfer): ?MoneyValueTransfer
    {
        return $this->getMoneyValue(
            $productAbstractTransfer,
            $this->priceProductFacade->getDefaultPriceTypeName()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductBvDealApiTransfer
     */
    protected function getBvDealApi(
        ProductAbstractTransfer $productAbstractTransfer
    ): ProductBvDealApiTransfer {
        $defaultPrice = $this->getDefaultPrice($productAbstractTransfer);
        $benefitPrice = $this->getBenefitPrice($productAbstractTransfer);
        $benefitAmount = $defaultPrice->getGrossAmount() - $benefitPrice->getGrossAmount();
        $productBcDealApi = new ProductBvDealApiTransfer();
        $productBcDealApi
            ->setBvItemPrice(
                $this->formatAmount(
                    $benefitPrice->getGrossAmount() / 100
                )
            )
            ->setBvAmount(
                $this->formatAmount(
                    $benefitAmount / 100
                )
            );

        return $productBcDealApi;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductSpDealApiTransfer
     */
    protected function getSpDealApi(
        ProductAbstractTransfer $productAbstractTransfer
    ): ProductSpDealApiTransfer {
        $spAmountKey = Config::get(MyWorldPaymentConstants::PRODUCT_ATTRIBUTE_KEY_SHOPPING_POINTS_AMOUNT);
        $benefitPrice = $this->getBenefitPrice($productAbstractTransfer);

        $productSpDealApi = new ProductSpDealApiTransfer();
        $productSpDealApi
            ->setSpItemPrice(
                $this->formatAmount(
                    $benefitPrice->getGrossAmount() / 100
                )
            )
            ->setSpAmount(
                $this->formatAmount(
                    $productAbstractTransfer->getAttributes()[$spAmountKey] ?? null
                )
            );

        return $productSpDealApi;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return bool|null
     */
    protected function getMarketerOnlyFlag(ProductAbstractTransfer $productAbstractTransfer): ?bool
    {
        $flagValue = $productAbstractTransfer->getAttributes()['marketer_only'] ?? false;

        return $flagValue === true || $flagValue === '1';
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return bool|null
     */
    protected function getCbwPrivateOnlyFlag(ProductAbstractTransfer $productAbstractTransfer): ?bool
    {
        $flagValue = $productAbstractTransfer->getAttributes()['cbw_private_only'] ?? false;

        return $flagValue === true || $flagValue === '1';
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
