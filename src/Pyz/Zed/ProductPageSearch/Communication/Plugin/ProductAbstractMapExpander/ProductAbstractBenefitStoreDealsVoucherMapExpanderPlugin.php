<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductPageSearch\Communication\Plugin\ProductAbstractMapExpander;

use Generated\Shared\Transfer\BvDealTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\PageMapTransfer;
use Generated\Shared\Transfer\SpDealTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductPageSearchExtension\Dependency\PageMapBuilderInterface;
use Spryker\Zed\ProductPageSearchExtension\Dependency\Plugin\ProductAbstractMapExpanderPluginInterface;

/**
 * @method \Pyz\Zed\ProductPageSearch\ProductPageSearchConfig getConfig()
 * @method \Pyz\Zed\ProductPageSearch\Communication\ProductPageSearchCommunicationFactory getFactory()
 */
class ProductAbstractBenefitStoreDealsVoucherMapExpanderPlugin extends AbstractPlugin implements ProductAbstractMapExpanderPluginInterface
{
    private const KEY_BENEFIT_DEALS = 'benefit-deals';
    private const KEY_BV_DEAL = 'bv-deal';
    private const KEY_SHOPPING_POINTS_DEALS = 'shopping-point-deals';
    private const KEY_SP_DEAL = 'sp-deal';
    private const KEY_ATTRIBUTES = 'attributes';
    private const KEY_ATTRIBUTE_PRODUCT_SP_AMOUNT = 'product_sp_amount';
    private const KEY_ATTRIBUTE_BENEFIT_STORE = 'benefit_store';
    private const KEY_ATTRIBUTE_SHOPPING_POINTS = 'shopping_points';
    private const KEY_ATTRIBUTE_SHOPPING_POINT_STORE = 'shopping_point_store';
    private const KEY_PRICES = 'prices';
    private const KEY_PRICES_GROSS_MODE = 'GROSS_MODE';
    private const KEY_PRICES_BENEFIT = 'BENEFIT';
    private const KEY_PRICES_DEFAULT = 'DEFAULT';

    /**
     * @param \Generated\Shared\Transfer\PageMapTransfer $pageMapTransfer
     * @param \Spryker\Zed\ProductPageSearchExtension\Dependency\PageMapBuilderInterface $pageMapBuilder
     * @param array $productData
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Generated\Shared\Transfer\PageMapTransfer
     */
    public function expandProductMap(
        PageMapTransfer $pageMapTransfer,
        PageMapBuilderInterface $pageMapBuilder,
        array $productData,
        LocaleTransfer $localeTransfer
    ): PageMapTransfer {
        $benefitStoreSalesPrice = $this->getBenefitStoreSalesPrice($productData);
        $shoppingPoint = $this->getShoppingPoint($productData);

        $benefitAmount = $this->getBenefitAmount($productData);
        $benefitStore = $this->getBenefitStore($productData);
        $productSpAmount = $this->getProductSpAmount($productData);
        $shoppingPointStore = $this->getShoppingPointStore($productData);

        $isBenefitDeals = $benefitStoreSalesPrice > 0 && $benefitAmount > 0 && $benefitStore > 0;
        $isShoppingPointDeals = $benefitStoreSalesPrice > 0 && $productSpAmount > 0 && $shoppingPointStore > 0;
        $pageMapTransfer->setBenefitDeals($isBenefitDeals);
        $pageMapTransfer->setShoppingPointDeals($isShoppingPointDeals);

        if ($isBenefitDeals) {
            $bvDeal = (new BvDealTransfer())->setBvItemPrice($benefitStoreSalesPrice)->setBvAmount($benefitAmount);
            $pageMapTransfer->setBvDeal($bvDeal);
            $pageMapBuilder->addSearchResultData($pageMapTransfer, self::KEY_BV_DEAL, $bvDeal->toArray());
        }
        if ($isShoppingPointDeals) {
            $spDeal = (new SpDealTransfer())->setSpItemPrice($benefitStoreSalesPrice)->setSpAmount($shoppingPoint);
            $pageMapTransfer->setSpDeal($spDeal);
            $pageMapBuilder->addSearchResultData($pageMapTransfer, self::KEY_SP_DEAL, $spDeal->toArray());
        }
        $pageMapBuilder->addSearchResultData($pageMapTransfer, self::KEY_BENEFIT_DEALS, $isBenefitDeals);
        $pageMapBuilder->addSearchResultData($pageMapTransfer, self::KEY_SHOPPING_POINTS_DEALS, $isShoppingPointDeals);

        return $pageMapTransfer;
    }

    /**
     * @param array $productData
     *
     * @return int
     */
    private function getBenefitStoreSalesPrice(array $productData): int
    {
        $prices = $productData[self::KEY_PRICES] ?? [];
        $benefitStoreSalesPrice = 0;

        foreach ($prices as $price) {
            $benefitStoreSalesPrice = $price[self::KEY_PRICES_GROSS_MODE][self::KEY_PRICES_BENEFIT] ?? 0;
            break;
        }

        return $benefitStoreSalesPrice;
    }

    /**
     * @param array $productData
     * @param string $currencyCode
     *
     * @return int
     */
    private function getDefaultPrice(array $productData, string $currencyCode = "EUR"): int
    {
        return $productData[self::KEY_PRICES][$currencyCode][self::KEY_PRICES_GROSS_MODE][self::KEY_PRICES_DEFAULT] ?? 0;
    }

    /**
     * @param array $productData
     *
     * @return int
     */
    private function getBenefitAmount(array $productData): int
    {
        $benefitPrice = $this->getBenefitStoreSalesPrice($productData);
        $defaultPrice = $this->getDefaultPrice($productData);

        return $defaultPrice - $benefitPrice;
    }

    /**
     * @param array $productData
     *
     * @return int
     */
    private function getBenefitStore(array $productData): int
    {
        return (int)($productData[self::KEY_ATTRIBUTES][self::KEY_ATTRIBUTE_BENEFIT_STORE] ?? 0);
    }

    /**
     * @param array $productData
     *
     * @return int
     */
    private function getShoppingPointStore(array $productData): int
    {
        return (int)($productData[self::KEY_ATTRIBUTES][self::KEY_ATTRIBUTE_SHOPPING_POINT_STORE] ?? 0);
    }

    /**
     * @param array $productData
     *
     * @return int
     */
    private function getProductSpAmount(array $productData): int
    {
        return (int)($productData[self::KEY_ATTRIBUTES][self::KEY_ATTRIBUTE_PRODUCT_SP_AMOUNT] ?? 0);
    }

    /**
     * @param array $productData
     *
     * @return int
     */
    private function getShoppingPoint(array $productData): int
    {
        return (int)($productData[self::KEY_ATTRIBUTES][self::KEY_ATTRIBUTE_SHOPPING_POINTS] ?? 0);
    }
}
