<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MyWorldPayment\Business\Calculator;

use Generated\Shared\Transfer\CalculableObjectTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;

class SubtotalWithDealsCalculator implements MyWorldPaymentQuoteCalculatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return \Generated\Shared\Transfer\CalculableObjectTransfer
     */
    public function recalculateQuote(CalculableObjectTransfer $calculableObjectTransfer): CalculableObjectTransfer
    {
        $subtotal = 0;

        /** @var \Generated\Shared\Transfer\ItemTransfer $itemTransfer */
        foreach ($calculableObjectTransfer->getItems() as $itemTransfer) {
            $price = $itemTransfer->getUnitPrice();

            if ($this->hasShoppingPointsDeals($itemTransfer)) {
                $price = $itemTransfer->getShoppingPointsDeal()->getPrice();
            }

            if ($this->hasBenefitVoucherDeals($itemTransfer)) {
                $price = $itemTransfer->getBenefitVoucherDealData()->getSalesPrice();
            }

            $subtotal += $price * $itemTransfer->getQuantity();
        }

        $calculableObjectTransfer->getTotals()->setSubtotalWithDeals($subtotal);

        return $calculableObjectTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return bool
     */
    protected function hasBenefitVoucherDeals(ItemTransfer $itemTransfer): bool
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
    protected function hasShoppingPointsDeals(ItemTransfer $itemTransfer): bool
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
