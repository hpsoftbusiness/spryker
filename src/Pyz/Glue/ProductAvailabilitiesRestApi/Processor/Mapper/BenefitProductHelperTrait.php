<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Glue\ProductAvailabilitiesRestApi\Processor\Mapper;

use Generated\Shared\Transfer\ProductViewTransfer;

trait BenefitProductHelperTrait
{
    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return int
     */
    protected function getCashbackAmount(ProductViewTransfer $productViewTransfer): int
    {
        $cashbackAmount = 0;
        if (!$this->isBenefitProduct($productViewTransfer)) {
            $cashbackAmount = $productViewTransfer->getAttributes()['cashback_amount'] ?? 0;
        }

        return $cashbackAmount;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return int
     */
    protected function getShoppingPointsAmount(ProductViewTransfer $productViewTransfer): int
    {
        $shoppingPointsAmount = 0;
        if (!$this->isBenefitProduct($productViewTransfer)) {
            $shoppingPointsAmount = $productViewTransfer->getAttributes()['shopping_points'] ?? 0;
        }

        return $shoppingPointsAmount;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return bool
     */
    protected function isBenefitProduct(ProductViewTransfer $productViewTransfer): bool
    {
        $isBenefitStore = $productViewTransfer->getAttributes()['benefit_store'] ?? false;
        $isShoppingPointStore = $productViewTransfer->getAttributes()['shopping_point_store'] ?? false;

        return $isBenefitStore || $isShoppingPointStore;
    }
}
