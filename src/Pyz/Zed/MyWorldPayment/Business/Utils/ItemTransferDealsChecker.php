<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MyWorldPayment\Business\Utils;

use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;

class ItemTransferDealsChecker implements ItemTransferDealsCheckerInterface
{
    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return bool
     */
    public function hasBenefitVoucherDeals(ItemTransfer $itemTransfer): bool
    {
        try {
            $itemTransfer->requireBenefitVoucherDealData();
            $itemTransfer->getBenefitVoucherDealData()->requireSalesPrice();
            $itemTransfer->getBenefitVoucherDealData()->requireAmount();
            $itemTransfer->getBenefitVoucherDealData()->requireIsStore();

            return $itemTransfer->getBenefitVoucherDealData()->getIsStore();
        } catch (RequiredTransferPropertyException $exception) {
            return false;
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return bool
     */
    public function hasShoppingPointsDeals(ItemTransfer $itemTransfer): bool
    {
        try {
            $itemTransfer->requireShoppingPointsDeal();
            $itemTransfer->getShoppingPointsDeal()->requireIsActive();
            $itemTransfer->getShoppingPointsDeal()->requireShoppingPointsQuantity();
            $itemTransfer->getShoppingPointsDeal()->requirePrice();

            return $itemTransfer->getShoppingPointsDeal()->getShoppingPointsQuantity() > 0
                && $itemTransfer->getShoppingPointsDeal()->getPrice() > 0;
        } catch (RequiredTransferPropertyException $exception) {
            return false;
        }
    }
}
