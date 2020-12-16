<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\ProductDetailPage\Controller;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use SprykerShop\Yves\ProductDetailPage\Controller\ProductController as SprykerShopProductController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Client\Product\ProductClientInterface getClient()
 * @method \Pyz\Yves\ProductDetailPage\ProductDetailPageFactory getFactory()
 */
class ProductController extends SprykerShopProductController
{
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
            ->setCashbackAmount($productAttributesData['cashback_amount'])
            ->setShoppingPoints($productAttributesData['shopping_points']);

        $viewData['product']
            ->setAttributes(
                $this->getFilterProductAttributes($viewData['product']->getAttributes())
            );

        if ($viewData['product']->getIsAffiliate()) {
            $affiliateData = $viewData['product']->getAffiliateData();
            $affiliateData['trackingUrl'] = $this->getProductAffiliateTrackingUrl($affiliateData);
            $viewData['product']->setAffiliateData($affiliateData);
        }

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
            return $affiliateData['affiliate_deeplink'];
        }

        return $this->getFactory()->getProductAffiliateService()
            ->generateProductAffiliateTrackingUrl(
                $affiliateData['affiliate_deeplink'],
                $customerTransfer
            );
    }

    /**
     * @param array $attributes
     *
     * @return array
     */
    protected function getFilterProductAttributes(array $attributes): array
    {
        $attributesToFilter = [
            'benefit_store',
            'shopping_point_store',
            'cashback_amount',
            'shopping_points',
        ];

        foreach (array_keys($attributes) as $attributeKey) {
            if (in_array($attributeKey, $attributesToFilter)) {
                unset($attributes[$attributeKey]);
            }
        }

        return $attributes;
    }
}
