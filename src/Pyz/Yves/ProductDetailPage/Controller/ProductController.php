<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\ProductDetailPage\Controller;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Pyz\Yves\ProductDetailPage\Exception\ProductAccessDeniedForUserNotLoggedInException;
use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Shared\Config\Config;
use SprykerShop\Yves\ProductDetailPage\Controller\ProductController as SprykerShopProductController;
use SprykerShop\Yves\ProductDetailPage\Exception\ProductAccessDeniedException;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Client\Product\ProductClientInterface getClient()
 * @method \Pyz\Yves\ProductDetailPage\ProductDetailPageConfig getConfig()
 * @method \Pyz\Yves\ProductDetailPage\ProductDetailPageFactory getFactory()
 */
class ProductController extends SprykerShopProductController
{
    private const KEY_AFFILIATE_DEEPLINK = 'affiliate_deeplink';

    /**
     * @param array $productData
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function detailAction(array $productData, Request $request)
    {
        try {
            $viewData = $this->executeDetailAction($productData, $request);
        } catch (ProductAccessDeniedForUserNotLoggedInException $e) {
            $redirectTo = Config::get(ApplicationConstants::BASE_URL_YVES) . $request->getPathInfo();

            return $this->redirectResponseExternal($this->getFactory()->getSsoClient()->getAuthorizeUrl($this->getLocale(), $redirectTo));
        }

        return $this->view(
            $viewData,
            $this->getFactory()->getProductDetailPageWidgetPlugins(),
            '@ProductDetailPage/views/pdp/pdp.twig'
        );
    }

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

        $viewData['product']
            ->setAttributes(
                $this->getFilterProductAttributes($viewData['product']->getAttributes())
            );

        if ($viewData['product']->getIsAffiliate()) {
            $affiliateData = $viewData['product']->getAffiliateData();
            $affiliateData['trackingUrl'] = $this->getProductAffiliateTrackingUrl(
                $affiliateData
            );
            $viewData['product']->setAffiliateData($affiliateData);
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
            'benefit_store',
            'shopping_point_store',
            'cashback_amount',
            'shopping_points',
            'taric_code',
            'purchase_price',
            'delivery_cost',
            'dropshipment_possible',
            'dropshipment_supplier',
            'office_dealer_id',
            'benefit_store_sales_price',
            'benefit_amount',
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
            'marketer_only',
            'featured_products',
            $this->getFactory()->getConfig()->getProductAttributeKeyBenefitStore(),
            $this->getFactory()->getConfig()->getProductAttributeKeyBenefitAmount(),
            $this->getFactory()->getConfig()->getProductAttributeKeyBenefitSalesPrice(),
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
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @throws \SprykerShop\Yves\ProductDetailPage\Exception\ProductAccessDeniedException
     * @throws \Pyz\Yves\ProductDetailPage\Exception\ProductAccessDeniedForUserNotLoggedInException
     *
     * @return void
     */
    protected function assertProductAbstractRestrictions(ProductViewTransfer $productViewTransfer): void
    {
        if (empty($productViewTransfer->getIdProductAbstract())) {
            return;
        }
        $productAbstractRestricted = $this->getFactory()
            ->getProductStorageClient()
            ->isProductAbstractRestricted($productViewTransfer->getIdProductAbstract());

        if (!$productAbstractRestricted) {
            return;
        }

        if ($this->getFactory()->getCustomerClient()->isLoggedIn()) {
            throw new ProductAccessDeniedException(static::GLOSSARY_KEY_PRODUCT_ACCESS_DENIED);
        }
        throw new ProductAccessDeniedForUserNotLoggedInException();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @throws \Pyz\Yves\ProductDetailPage\Exception\ProductAccessDeniedForUserNotLoggedInException
     * @throws \SprykerShop\Yves\ProductDetailPage\Exception\ProductAccessDeniedException
     *
     * @return void
     */
    protected function assertProductConcreteRestrictions(ProductViewTransfer $productViewTransfer): void
    {
        if (empty($productViewTransfer->getIdProductConcrete())) {
            return;
        }

        $productConcreteRestricted = $this->getFactory()
            ->getProductStorageClient()
            ->isProductConcreteRestricted($productViewTransfer->getIdProductConcrete());

        if (!$productConcreteRestricted) {
            return;
        }

        if ($this->getFactory()->getCustomerClient()->isLoggedIn()) {
            throw new ProductAccessDeniedException(static::GLOSSARY_KEY_PRODUCT_ACCESS_DENIED);
        }
        throw new ProductAccessDeniedForUserNotLoggedInException();
    }
}
