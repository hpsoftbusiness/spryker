<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\ProductAffiliateOffersPriceWidget\Widget;

use Generated\Shared\Transfer\CurrentProductPriceTransfer;
use Generated\Shared\Transfer\ProductOfferStorageCriteriaTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \Pyz\Yves\ProductAffiliateOffersPriceWidget\ProductAffiliateOffersPriceWidgetFactory getFactory()
 * @method \Pyz\Yves\ProductAffiliateOffersPriceWidget\ProductAffiliateOffersPriceWidgetConfig getConfig()
 */
class ProductAffiliateOffersPriceWidget extends AbstractWidget
{
    public const NAME = 'ProductAffiliateOffersPriceWidget';
    private const PARAMETER_PRICE_NAME = 'price';

    /**
     * @param int $abstractProduct
     */
    public function __construct(int $abstractProduct)
    {
        $this->addParameter(static::PARAMETER_PRICE_NAME, $this->getLowerPrice($abstractProduct));
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return static::NAME;
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@ProductAffiliateOffersPriceWidget/views/product-affiliate-offers-price.twig';
    }

    /**
     * @param int $abstractProductId
     *
     * @return \Generated\Shared\Transfer\CurrentProductPriceTransfer|null
     */
    private function getLowerPrice(int $abstractProductId): ?CurrentProductPriceTransfer
    {
        $locale = $this->getLocale();
        $abstractProducts = $this->getFactory()->getProductStorageClient()->getProductAbstractViewTransfers(
            [$abstractProductId],
            $locale
        );
        $concretes = [];

        foreach ($abstractProducts as $abstractProduct) {
            $concretes = array_merge(
                $concretes,
                array_keys($abstractProduct->getAttributeMap()->getProductConcreteIds())
            );
        }

        $productOfferCriteriaFilterTransfer = new ProductOfferStorageCriteriaTransfer();
        $productOfferCriteriaFilterTransfer->setProductConcreteSkus(array_values($concretes));

        if ($this->getConfig()->isMultiCountryFeatureEnabled()) {
            $productOfferCriteriaFilterTransfer->setSellableIso2Code($this->getSellableCountryCode());
        }

        $productOfferStorageCollectionTransfer = $this->getFactory()->getMerchantProductOfferStorageClient(
        )->getProductOffersBySkus(
            $productOfferCriteriaFilterTransfer
        );
        $price = null;

        if (!$productOfferStorageCollectionTransfer->getProductOffersStorage()->count(
        ) && isset($abstractProducts[0])) {
            return (new CurrentProductPriceTransfer())->fromArray(
                $abstractProducts[0]->toArray(),
                true
            );
        }

        foreach ($productOfferStorageCollectionTransfer->getProductOffersStorage() as $offerStorageTransfer) {
            if ($price === null) {
                $price = $offerStorageTransfer->getPrice();
                continue;
            }
            if ($price->getPrice() > $offerStorageTransfer->getPrice()->getPrice()) {
                $price = $offerStorageTransfer->getPrice();
            }
        }

        return $price;
    }

    /**
     * @return string
     */
    private function getSellableCountryCode(): string
    {
        return $this->getFactory()->getStore()->getCurrentCountry();
    }
}
