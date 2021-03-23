<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Glue\CatalogSearchRestApi\Plugin\ProductAbstractExpander;

use Generated\Shared\Transfer\RestCatalogSearchAbstractProductsTransfer;
use Pyz\Glue\CatalogSearchRestApi\Dependency\Plugin\CatalogSearchAbstractProductExpanderPluginInterface;
use Spryker\Glue\Kernel\AbstractPlugin;

class ProductAbstractAttributesExpanderPlugin extends AbstractPlugin implements CatalogSearchAbstractProductExpanderPluginInterface
{
    private const ATTRIBUTE_BRAND = 'brand';
    private const ATTRIBUTE_SHOPPING_POINTS = 'shopping_points';
    private const ATTRIBUTE_CASHBACK_AMOUNT = 'cashback_amount';

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
        $this->setBrand($abstractProductData, $restCatalogSearchAbstractProductsTransfer);
        $this->setShoppingPoints($abstractProductData, $restCatalogSearchAbstractProductsTransfer);
        $this->setCashbackAmount($abstractProductData, $restCatalogSearchAbstractProductsTransfer);
    }

    /**
     * @param array $abstractProductData
     * @param \Generated\Shared\Transfer\RestCatalogSearchAbstractProductsTransfer $restCatalogSearchAbstractProductsTransfer
     *
     * @return void
     */
    private function setBrand(
        array $abstractProductData,
        RestCatalogSearchAbstractProductsTransfer $restCatalogSearchAbstractProductsTransfer
    ): void {
        $brandValue = $this->extractSingularAttributeValue($abstractProductData[self::ATTRIBUTE_BRAND] ?? null);
        if ($brandValue) {
            $restCatalogSearchAbstractProductsTransfer->setBrand((string)$brandValue);
        }
    }

    /**
     * @param array $abstractProductData
     * @param \Generated\Shared\Transfer\RestCatalogSearchAbstractProductsTransfer $restCatalogSearchAbstractProductsTransfer
     *
     * @return void
     */
    private function setCashbackAmount(
        array $abstractProductData,
        RestCatalogSearchAbstractProductsTransfer $restCatalogSearchAbstractProductsTransfer
    ): void {
        $cashbackAmountValue = $this->extractSingularAttributeValue(
            $abstractProductData[self::ATTRIBUTE_CASHBACK_AMOUNT] ?? null
        );
        if (!empty($cashbackAmountValue)) {
            $restCatalogSearchAbstractProductsTransfer->setCashbackAmount((int)$cashbackAmountValue);
        }
    }

    /**
     * @param array $abstractProductData
     * @param \Generated\Shared\Transfer\RestCatalogSearchAbstractProductsTransfer $restCatalogSearchAbstractProductsTransfer
     *
     * @return void
     */
    private function setShoppingPoints(
        array $abstractProductData,
        RestCatalogSearchAbstractProductsTransfer $restCatalogSearchAbstractProductsTransfer
    ): void {
        $shoppingPointsValue = $this->extractSingularAttributeValue(
            $abstractProductData[self::ATTRIBUTE_SHOPPING_POINTS] ?? null
        );
        if (!empty($shoppingPointsValue)) {
            $restCatalogSearchAbstractProductsTransfer->setShoppingPoints((int)$shoppingPointsValue);
        }
    }

    /**
     * @param mixed $attributeValue
     *
     * @return mixed
     */
    private function extractSingularAttributeValue($attributeValue)
    {
        if (!is_array($attributeValue)) {
            return $attributeValue;
        }

        if (!count($attributeValue)) {
            return null;
        }

        return current($attributeValue);
    }
}
