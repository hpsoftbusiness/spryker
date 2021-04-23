<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\ProductDetailPage\Controller;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use SprykerShop\Yves\ProductDetailPage\Controller\ProductController as SprykerShopProductController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Client\Product\ProductClientInterface getClient()
 * @method \Pyz\Yves\ProductDetailPage\ProductDetailPageFactory getFactory()
 */
class ProductController extends SprykerShopProductController
{
    private const KEY_AFFILIATE_DEEPLINK = 'affiliate_deeplink';

    /**
     * @param array $productData
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    protected function executeDetailAction(array $productData, Request $request): array
    {
        $viewData = parent::executeDetailAction($productData, $request);

        $quoteTransfer = new QuoteTransfer();
        $quoteTransfer->addItem(
            (new ItemTransfer())->setIdProductAbstract($viewData['product']->getIdProductAbstract())
        );
        $viewData['cart'] = $quoteTransfer;

        $productAttributesData = $viewData['product']->getAttributes();
        $viewData['product']
            ->setCashbackAmount($productAttributesData['cashback_amount'] ?? null)
            ->setShoppingPoints($productAttributesData['shopping_points'] ?? null);

        $this->hydrateProductViewWithBenefitDealData($viewData['product'], $productAttributesData);

        $viewData['product']
            ->setAttributes(
                $this->getFilterProductAttributes($viewData['product']->getAttributes())
            );
        foreach ($viewData['product']['bundledProducts'] as $bundledProduct) {
            $bundledProduct->setAttributes(
                $this->getFilterBundleProductAttributes($bundledProduct->getAttributes())
            );
        }

//        if ($viewData['product']->getIsAffiliate()) {
//            $affiliateData = $viewData['product']->getAffiliateData();
//            $affiliateData['trackingUrl'] = $this->getProductAffiliateTrackingUrl(
//                $affiliateData
//            );
//            $viewData['product']->setAffiliateData($affiliateData);
//        }

        return $viewData;
    }

    /**
     * @param mixed[] $affiliateData
     *
     * @return string
     */
    protected function getProductAffiliateTrackingUrl(array $affiliateData): string
    {
        $customerTransfer = $this->getFactory()->getCustomerClient()->getCustomer();

        if (!$customerTransfer) {
            return $affiliateData[self::KEY_AFFILIATE_DEEPLINK] ?? '';
        }

        return $this->getFactory()->getProductAffiliateService()
            ->generateProductAffiliateTrackingUrl(
                $affiliateData,
                $customerTransfer
            );
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param array $attributes
     *
     * @return void
     */
    protected function hydrateProductViewWithBenefitDealData(
        ProductViewTransfer $productViewTransfer,
        array $attributes
    ): void {
        if ($attributes[$this->getFactory()->getConfig()->getProductAttributeKeyBenefitStore()] ?? null) {
            $benefitSalesPrice = $attributes[$this->getFactory()->getConfig()->getProductAttributeKeyBenefitSalesPrice()] ?? null;
            $benefitAmount = $attributes[$this->getFactory()->getConfig()->getProductAttributeKeyBenefitAmount()] ?? null;
            /**
             * @todo Remove conversions once benefit sales price and amount import is updated to convert it to cents (integer)
             */
            if ($benefitSalesPrice !== null) {
                $benefitSalesPrice = $this->getFactory()->createDecimalToIntegerConverter()->convert(
                    (float)$benefitSalesPrice
                );
            }

            if ($benefitAmount !== null) {
                $benefitAmount = $this->getFactory()->createDecimalToIntegerConverter()->convert(
                    (float)$benefitAmount
                );
            }

            $productViewTransfer
                ->setBenefitAmount($benefitAmount)
                ->setBenefitSalesPrice($benefitSalesPrice);
        } else {
            $productViewTransfer->setShoppingPointsAmount(
                $attributes[$this->getFactory()->getConfig()->getShoppingPointsAmountAttributeName()] ?? null
            );
        }
    }

    /**
     * @param array $attributes
     *
     * @return array
     */
    protected function getFilterProductAttributes(array $attributes): array
    {
        // TODO: enable later, this should not be Zed request because of performance concerns
//        $productAttributeKeysCollectionTransfer = new ProductAttributeKeysCollectionTransfer();
//        $productAttributeKeysCollectionTransfer->setKeys([]);
//        $keysToShowOnPdp = $this->getFactory()
//            ->getProductAttributeClient()
//            ->getKeysToShowOnPdp($productAttributeKeysCollectionTransfer)
//            ->getKeys();
//        $attributes = array_intersect_key($attributes, array_flip($keysToShowOnPdp));

        $attributesToFilter = [
            'shopping_point_store',
            'cashback_amount',
            'shopping_points',
            'taric_code',
            'purchase_price',
            'delivery_cost',
            'dropshipment_possible',
            'dropshipment_supplier',
            'office_dealer_id',
            'base_price_display_value',
            'base_price_net_value',
            'base_price_unit',
            'regular_sales_price',
            'strike_price',
            'customer_group_1',
            'customer_group_2',
            'customer_group_3',
            'customer_group_4',
            'customer_group_5',
            'product_sp_amount',
        ];

        foreach (array_keys($attributes) as $attributeKey) {
            if (in_array($attributeKey, $attributesToFilter) || strpos($attributeKey, 'sellable_') !== false) {
                unset($attributes[$attributeKey]);
            }
        }

        return array_filter($attributes, function ($value) {
            return $value !== null && $value !== '' && $value !== false;
        });
    }

    /**
     * @param array $attributes
     *
     * @return array
     */
    protected function getFilterBundleProductAttributes(array $attributes): array
    {
        $attributesToFilter = [
            'manufacturer',
            'brand',
            'color',
            'size',
            'gender',
            'ean',
            'gtin',
            'mpn',
        ];

        return array_intersect_key($attributes, array_flip($attributesToFilter));
    }
}
