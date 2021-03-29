<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Glue\CatalogSearchRestApi\Plugin\ProductAbstractExpander;

use Generated\Shared\Transfer\RestCatalogSearchAbstractProductsTransfer;
use Generated\Shared\Transfer\RestCatalogSearchProductBenefitTransfer;
use Pyz\Glue\CatalogSearchRestApi\Dependency\Plugin\CatalogSearchAbstractProductExpanderPluginInterface;
use Pyz\Glue\CatalogSearchRestApi\Plugin\ProductAbstractExpander\Traits\SingularAttributeValueHelperTrait;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

class ProductAbstractBenefitExpanderPlugin extends AbstractPlugin implements CatalogSearchAbstractProductExpanderPluginInterface
{
    use SingularAttributeValueHelperTrait;

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
        $cashbackAmountValue = $this->getCashbackAmount($abstractProductData);
        $shoppingPointValue = $this->getShoppingPoint($abstractProductData);
        if ($cashbackAmountValue === null && $shoppingPointValue === null) {
            return;
        }

        $benefitTransfer = new RestCatalogSearchProductBenefitTransfer();
        $benefitTransfer->setCashbackAmount($cashbackAmountValue);
        $benefitTransfer->setShoppingPoints($shoppingPointValue);
        $restCatalogSearchAbstractProductsTransfer->setBenefit($benefitTransfer);
    }

    /**
     * @param array $abstractProductData
     *
     * @return int|null
     */
    private function getCashbackAmount(array $abstractProductData): ?int
    {
        $cashbackAmountValue = $this->extractSingularAttributeValue(
            $abstractProductData[self::ATTRIBUTE_CASHBACK_AMOUNT] ?? null
        );
        if (empty($cashbackAmountValue)) {
            return null;
        }

        return (int)$cashbackAmountValue;
    }

    /**
     * @param array $abstractProductData
     *
     * @return int|null
     */
    private function getShoppingPoint(array $abstractProductData): ?int
    {
        $shoppingPointsValue = $this->extractSingularAttributeValue(
            $abstractProductData[self::ATTRIBUTE_SHOPPING_POINTS] ?? null
        );
        if (empty($shoppingPointsValue)) {
            return null;
        }

        return (int)$shoppingPointsValue;
    }
}
