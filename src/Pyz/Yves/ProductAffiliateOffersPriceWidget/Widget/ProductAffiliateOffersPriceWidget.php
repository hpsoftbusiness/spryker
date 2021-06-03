<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\ProductAffiliateOffersPriceWidget\Widget;

use Generated\Shared\Transfer\CurrentProductPriceTransfer;
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
        $productOfferStorageCollectionTransfer = $this->getFactory()
            ->getProductAbstractOffersClient()
            ->getProductOffersByAbstractId($abstractProductId, $this->getLocale());

        $price = null;

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
}
