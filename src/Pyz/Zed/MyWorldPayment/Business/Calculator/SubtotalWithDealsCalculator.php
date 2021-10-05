<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MyWorldPayment\Business\Calculator;

use Generated\Shared\Transfer\CalculableObjectTransfer;
use Pyz\Zed\MyWorldPayment\Business\Utils\ItemTransferDealsCheckerInterface;

class SubtotalWithDealsCalculator implements MyWorldPaymentQuoteCalculatorInterface
{
    /**
     * @var \Pyz\Zed\MyWorldPayment\Business\Utils\ItemTransferDealsCheckerInterface
     */
    protected $itemTransferDealsChecker;

    /**
     * @param \Pyz\Zed\MyWorldPayment\Business\Utils\ItemTransferDealsCheckerInterface $itemTransferDealsChecker
     */
    public function __construct(ItemTransferDealsCheckerInterface $itemTransferDealsChecker)
    {
        $this->itemTransferDealsChecker = $itemTransferDealsChecker;
    }

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

            if ($this->itemTransferDealsChecker->hasShoppingPointsDeals($itemTransfer)) {
                $price = $itemTransfer->getShoppingPointsDeal()->getPrice();
            }

            if ($this->itemTransferDealsChecker->hasBenefitVoucherDeals($itemTransfer)) {
                $price = $itemTransfer->getBenefitVoucherDealData()->getSalesPrice();
            }

            $subtotal += $price * $itemTransfer->getQuantity();
        }

        $calculableObjectTransfer->getTotals()->setSubtotalWithDeals($subtotal);

        return $calculableObjectTransfer;
    }
}
