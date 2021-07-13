<?php
declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MyWorldPayment\Business\Calculator\SegmentNumber;

use Generated\Shared\Transfer\CalculableObjectTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Pyz\Zed\MyWorldPayment\Business\Calculator\MyWorldPaymentOrderCalculatorInterface;
use Pyz\Zed\MyWorldPayment\Business\Calculator\MyWorldPaymentQuoteCalculatorInterface;

class SegmentNumberZeroMarginCalculator implements
    MyWorldPaymentQuoteCalculatorInterface,
    MyWorldPaymentOrderCalculatorInterface
{
    protected const SEGMENT_NUMBER_ZERO_MARGIN = 4;

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return \Generated\Shared\Transfer\CalculableObjectTransfer
     */
    public function recalculateQuote(CalculableObjectTransfer $calculableObjectTransfer): CalculableObjectTransfer
    {
        $items = $calculableObjectTransfer->getItems();
        foreach ($items as $item) {
            if ($this->isBenefitVoucherUsed($item) || $this->isShoppingPointUsed($item)) {
                $item->setSegmentNumber(static::SEGMENT_NUMBER_ZERO_MARGIN);
            }
        }

        return $calculableObjectTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return \Generated\Shared\Transfer\CalculableObjectTransfer
     */
    public function recalculateOrder(CalculableObjectTransfer $calculableObjectTransfer): CalculableObjectTransfer
    {
        return $this->recalculateQuote($calculableObjectTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return bool
     */
    protected function isBenefitVoucherUsed(ItemTransfer $itemTransfer): bool
    {
        return $itemTransfer->getUseBenefitVoucher()
            && $itemTransfer->getTotalUsedBenefitVouchersAmount() > 0;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return bool
     */
    protected function isShoppingPointUsed(ItemTransfer $itemTransfer): bool
    {
        return $itemTransfer->getUseShoppingPoints()
            && $itemTransfer->getTotalUsedShoppingPointsAmount() > 0;
    }
}
