<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\ProductStorage\Plugin;

use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\ProductStorageExtension\Dependency\Plugin\ProductViewExpanderPluginInterface;

/**
 * @method \Pyz\Client\ProductStorage\ProductStorageFactory getFactory()
 */
class ProductViewBenefitDealExpanderPlugin extends AbstractPlugin implements ProductViewExpanderPluginInterface
{
    /**
     * @var \Spryker\Shared\Money\Converter\DecimalToIntegerConverter
     */
    protected $decimalToIntegerConverter;

    public function __construct()
    {
        $this->decimalToIntegerConverter = $this->getFactory()->createDecimalToIntegerConverter();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param array $productData
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer
     */
    public function expandProductViewTransfer(
        ProductViewTransfer $productViewTransfer,
        array $productData,
        $localeName
    ) {
        $attributes = $productViewTransfer->getAttributes();
        $benefitStoreKey = $this->getFactory()->getConfig()->getProductAttributeKeyBenefitStore();
        $benefitSalesPriceKey = $this->getFactory()->getConfig()->getProductAttributeKeyBenefitSalesPrice();
        $benefitAmountKey = $this->getFactory()->getConfig()->getProductAttributeKeyBenefitAmount();
        $shoppingPointsStoreKey = $this->getFactory()->getConfig()->getProductAttributeKeyShoppingPointsStore();
        $shoppingPointsAmountKey = $this->getFactory()->getConfig()->getProductAttributeKeyShoppingPointsAmount();

        $productViewTransfer
            ->setCashbackAmount($attributes['cashback_amount'] ?? null)
            ->setShoppingPoints($attributes['shopping_points'] ?? null);

        if (!empty($attributes[$benefitStoreKey])) {
            $benefitSalesPrice = $attributes[$benefitSalesPriceKey] ?? null;
            $benefitAmount = $attributes[$benefitAmountKey] ?? null;
            $benefitSalesPrice = str_replace(',', '.', $benefitSalesPrice);
            $benefitAmount = str_replace(',', '.', $benefitAmount);

            /**
             * @todo Remove conversions once benefit sales price and amount import is updated to convert it to cents (integer)
             */
            if ($benefitSalesPrice !== null) {
                $benefitSalesPrice = $this->decimalToIntegerConverter->convert(
                    (float)$benefitSalesPrice
                );
            }

            if ($benefitAmount !== null) {
                $benefitAmount = $this->decimalToIntegerConverter->convert(
                    (float)$benefitAmount
                );
            }

            $productViewTransfer
                ->setBenefitAmount($benefitAmount)
                ->setBenefitSalesPrice($benefitSalesPrice);
        }

        if (!empty($attributes[$shoppingPointsStoreKey])) {
            $productViewTransfer->setShoppingPointsAmount(
                $attributes[$shoppingPointsAmountKey] ?? null
            );
        }

        return $productViewTransfer;
    }
}
