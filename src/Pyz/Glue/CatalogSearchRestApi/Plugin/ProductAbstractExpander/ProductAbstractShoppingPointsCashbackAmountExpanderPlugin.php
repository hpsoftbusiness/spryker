<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Glue\CatalogSearchRestApi\Plugin\ProductAbstractExpander;

use Generated\Shared\Transfer\RestCatalogSearchAbstractProductsTransfer;
use Pyz\Glue\CatalogSearchRestApi\Dependency\Plugin\CatalogSearchAbstractProductExpanderPluginInterface;
use Pyz\Glue\CatalogSearchRestApi\Plugin\ProductAbstractExpander\Traits\SingularAttributeValueHelperTrait;
use Spryker\Glue\Kernel\AbstractPlugin;

class ProductAbstractShoppingPointsCashbackAmountExpanderPlugin extends AbstractPlugin implements CatalogSearchAbstractProductExpanderPluginInterface
{
    use SingularAttributeValueHelperTrait;

    private const ATTRIBUTES_KEY = 'attributes';
    private const ATTRIBUTE_SHOPPING_POINTS = 'shopping_points';
    private const ATTRIBUTE_CASHBACK_AMOUNT = 'cashback_amount';
    private const ATTRIBUTE_SHOPPING_POINT_DEAL = 'shopping-point-deals';
    private const ATTRIBUTE_BENEFIT_DEAL = 'benefit-deals';

    /**
     * @param array $abstractProductData
     * @param \Generated\Shared\Transfer\RestCatalogSearchAbstractProductsTransfer $restCatalogSearchAbstractProductsTransfer
     *
     * @return void
     */
    public function expand(
        array $abstractProductData,
        RestCatalogSearchAbstractProductsTransfer $restCatalogSearchAbstractProductsTransfer
    ): void {
        $this->setShoppingPointDeals($abstractProductData, $restCatalogSearchAbstractProductsTransfer);
    }

    /**
     * @param array $abstractProductData
     * @param \Generated\Shared\Transfer\RestCatalogSearchAbstractProductsTransfer $restCatalogSearchAbstractProductsTransfer
     *
     * @return void
     */
    private function setShoppingPointDeals(
        array $abstractProductData,
        RestCatalogSearchAbstractProductsTransfer $restCatalogSearchAbstractProductsTransfer
    ): void {
        $restCatalogSearchAbstractProductsTransfer
            ->setShoppingPoints(0)
            ->setCashbackAmount(0);

        $shoppingPointDeal = (bool)$this->extractSingularAttributeValue(
            $abstractProductData[self::ATTRIBUTE_SHOPPING_POINT_DEAL] ?? null
        );
        $benefitDeal = (bool)$this->extractSingularAttributeValue(
            $abstractProductData[self::ATTRIBUTE_BENEFIT_DEAL] ?? null
        );
        if (!$shoppingPointDeal && !$benefitDeal) {
            $cashbackAmount = $this->getCashbackAmount($abstractProductData);
            $shoppingPoints = $this->getShoppingPoint($abstractProductData);
            $restCatalogSearchAbstractProductsTransfer->setCashbackAmount($cashbackAmount);
            $restCatalogSearchAbstractProductsTransfer->setShoppingPoints($shoppingPoints);
        }
    }

    /**
     * @param array $abstractProductData
     *
     * @return int
     */
    private function getCashbackAmount(array $abstractProductData): int
    {
        $cashbackAmountValue = $this->extractSingularAttributeValue(
            $abstractProductData[self::ATTRIBUTES_KEY][self::ATTRIBUTE_CASHBACK_AMOUNT] ?? 0
        );
        if (empty($cashbackAmountValue)) {
            return 0;
        }

        return (int)$cashbackAmountValue;
    }

    /**
     * @param array $abstractProductData
     *
     * @return int
     */
    private function getShoppingPoint(array $abstractProductData): int
    {
        $shoppingPointsValue = $this->extractSingularAttributeValue(
            $abstractProductData[self::ATTRIBUTES_KEY][self::ATTRIBUTE_SHOPPING_POINTS] ?? 0
        );
        if (empty($shoppingPointsValue)) {
            return 0;
        }

        return (int)$shoppingPointsValue;
    }
}
