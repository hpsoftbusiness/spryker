<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\Weclapp\Client\Article;

use Generated\Shared\Transfer\LocalizedAttributesTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\WeclappArticleTransfer;
use Pyz\Client\Weclapp\Exception\LocalizedAttributesNotFoundException;
use Pyz\Shared\Weclapp\WeclappConfig;

class ArticleHydrator implements ArticleHydratorInterface
{
    protected const ARTICLE_TYPE = 'STORABLE';
    protected const TAX_RATE_TYPE = 'STANDARD';
    protected const UNIT_NAME = 'Stk.';
    protected const PROCUREMENT_LEAD_DAYS = 0;

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productTransfer
     * @param \Generated\Shared\Transfer\WeclappArticleTransfer|null $weclappArticleTransfer
     *
     * @return \Generated\Shared\Transfer\WeclappArticleTransfer
     */
    public function hydrateProductToWeclappArticle(
        ProductConcreteTransfer $productTransfer,
        ?WeclappArticleTransfer $weclappArticleTransfer = null
    ): WeclappArticleTransfer {
        if (!$weclappArticleTransfer) {
            $weclappArticleTransfer = new WeclappArticleTransfer();
        }

        $localizedAttributes = $this->getLocalizedAttributes($productTransfer);
        /** @var string $productTransferAttributes */
        $productTransferAttributes = $productTransfer->getAttributes();
        /** @var string $localizedAttributesAttributes */
        $localizedAttributesAttributes = $localizedAttributes->getAttributes();
        $attributes = array_merge(
            json_decode($productTransferAttributes, true),
            json_decode($localizedAttributesAttributes, true)
        );

        $weclappArticleTransfer->setApplyCashDiscount(false)
            ->setArticleNumber($productTransfer->getSkuOrFail())
            ->setArticleType(static::ARTICLE_TYPE)
            ->setSystemCode($productTransfer->getAbstractSkuOrFail())
            ->setAvailableInSale(true)
            ->setAvailableInShop(false)
            ->setBillOfMaterialPartDeliveryPossible(false)
            ->setBatchNumberRequired(false)
            ->setName($localizedAttributes->getNameOrFail())
            ->setTaxRateType(static::TAX_RATE_TYPE)
            ->setUnitName(static::UNIT_NAME)
            ->setEan($this->getAttribute($attributes, 'ean') ?? $weclappArticleTransfer->getEan())
            ->setCountryOfOriginCode(
                $productTransfer->getCountryOfOrigin()
                    ? $productTransfer->getCountryOfOrigin()->getIso2CodeOrFail()
                    : $weclappArticleTransfer->getCountryOfOriginCode()
            )
            ->setManufacturerPartNumber(
                $this->getAttribute($attributes, 'mpn')
                ?? $weclappArticleTransfer->getManufacturerPartNumber()
            )
            ->setManufacturerName(
                $this->getAttribute($attributes, 'manufacturer')
                ?? $weclappArticleTransfer->getManufacturerName()
            )
            ->setArticleLength(
                $this->mapAttributeToWeclappNumber($attributes, 'length')
                ?? $weclappArticleTransfer->getArticleLength()
            )
            ->setArticleWidth(
                $this->mapAttributeToWeclappNumber($attributes, 'width')
                ?? $weclappArticleTransfer->getArticleWidth()
            )
            ->setArticleHeight(
                $this->mapAttributeToWeclappNumber($attributes, 'height')
                ?? $weclappArticleTransfer->getArticleHeight()
            )
            ->setArticleNetWeight(
                $this->mapAttributeToWeclappNumber($attributes, 'weight')
                ?? $weclappArticleTransfer->getArticleNetWeight()
            )
            ->setArticleGrossWeight(
                $this->mapAttributeToWeclappNumber($attributes, 'weight_total')
                ?? $weclappArticleTransfer->getArticleGrossWeight()
            )
            ->setActive($productTransfer->getIsActive())
            ->setCustomsTariffNumber(
                $this->getAttribute($attributes, 'taric_code')
                ?? $weclappArticleTransfer->getCustomsTariffNumber()
            )
            ->setArticleCategoryId(
                $this->mapToWeclappCategoryId($productTransfer)
                ?? $weclappArticleTransfer->getArticleCategoryId()
            )
            ->setProcurementLeadDays(static::PROCUREMENT_LEAD_DAYS)
            ->setProductionArticle(false)
            ->setSerialNumberRequired(false)
            ->setShowOnDeliveryNote(true)
            ->setUseSalesBillOfMaterialItemPrices(false)
            ->setUseSalesBillOfMaterialItemPricesForPurchase(false)
            ->setUseAvailableForSalesChannels(false);

        return $weclappArticleTransfer;
    }

    /**
     * @param array $weclappArticleData
     *
     * @return \Generated\Shared\Transfer\WeclappArticleTransfer
     */
    public function mapWeclappDataToWeclappArticle(array $weclappArticleData): WeclappArticleTransfer
    {
        return (new WeclappArticleTransfer())->fromArray($weclappArticleData, true);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productTransfer
     *
     * @throws \Pyz\Client\Weclapp\Exception\LocalizedAttributesNotFoundException
     *
     * @return \Generated\Shared\Transfer\LocalizedAttributesTransfer
     */
    protected function getLocalizedAttributes(ProductConcreteTransfer $productTransfer): LocalizedAttributesTransfer
    {
        foreach ($productTransfer->getLocalizedAttributes() as $localizedAttributes) {
            if ($localizedAttributes->getLocaleOrFail()->getLocaleNameOrFail() === WeclappConfig::LOCALE_CODE) {
                return $localizedAttributes;
            }
        }

        throw new LocalizedAttributesNotFoundException();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productTransfer
     *
     * @return string|null
     */
    protected function mapToWeclappCategoryId(ProductConcreteTransfer $productTransfer): ?string
    {
        $categories = $productTransfer->getCategoryCollectionOrFail()->getCategories();
        if (isset($categories[0])) {
            return $categories[0]->getIdWeclappArticleCategory();
        }

        return null;
    }

    /**
     * @param array $attributes
     * @param string $key
     *
     * @return string|null
     */
    protected function mapAttributeToWeclappNumber(array $attributes, string $key): ?string
    {
        $value = $this->getAttribute($attributes, $key);
        if ($value === null) {
            return null;
        }

        return (string)(str_replace(',', '.', $value) / 100);
    }

    /**
     * @param array $attributes
     * @param string $key
     *
     * @return string|null
     */
    protected function getAttribute(array $attributes, string $key): ?string
    {
        if (!isset($attributes[$key]) || $attributes[$key] === '') {
            return null;
        }

        return (string)$attributes[$key];
    }
}
