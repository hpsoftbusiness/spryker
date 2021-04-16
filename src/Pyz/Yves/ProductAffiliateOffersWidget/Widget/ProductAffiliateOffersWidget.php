<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\ProductAffiliateOffersWidget\Widget;

use Generated\Shared\Transfer\ProductOfferStorageCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferStorageCriteriaTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \Pyz\Yves\ProductAffiliateOffersWidget\ProductAffiliateOffersWidgetFactory getFactory()
 */
class ProductAffiliateOffersWidget extends AbstractWidget
{
    public const NAME = 'ProductAffiliateOffersWidget';
    private const PARAMETER_OFFER_NAME = 'offers';

    /**
     * @param int $abstractProduct
     */
    public function __construct(int $abstractProduct)
    {
        $this->addParameter(
            static::PARAMETER_OFFER_NAME,
            $this->getFactory()->createAffiliateDataProvider()->getData($this->getProductOffers($abstractProduct))
        );
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
        return '@ProductAffiliateOffersWidget/views/product-affiliate-offers.twig';
    }

    /**
     * @param int $abstractProductId
     *
     * @return \Generated\Shared\Transfer\ProductOfferStorageCollectionTransfer
     */
    private function getProductOffers(int $abstractProductId): ProductOfferStorageCollectionTransfer
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

        return $this->getFactory()->getMerchantProductOfferStorageClient()->getProductOffersBySkus(
            $productOfferCriteriaFilterTransfer
        );
    }
}
