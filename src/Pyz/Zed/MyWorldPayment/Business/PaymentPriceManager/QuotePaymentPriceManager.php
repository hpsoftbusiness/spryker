<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MyWorldPayment\Business\PaymentPriceManager;

use Generated\Shared\Transfer\AvailableInternalPaymentAmountTransfer;
use Generated\Shared\Transfer\CalculableObjectTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

class QuotePaymentPriceManager implements PaymentPriceManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return \Generated\Shared\Transfer\AvailableInternalPaymentAmountTransfer
     */
    public function getAvailablePriceToPayByCalculableObject(CalculableObjectTransfer $calculableObjectTransfer): AvailableInternalPaymentAmountTransfer
    {
        $quote = (new QuoteTransfer())
            ->fromArray($calculableObjectTransfer->toArray(), true)
            ->setCustomer($calculableObjectTransfer->getOriginalQuote()->getCustomer())
            ->setTotals($calculableObjectTransfer->getTotals());

        return $this->getAvailablePriceToPay($quote);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\AvailableInternalPaymentAmountTransfer
     */
    public function getAvailablePriceToPay(QuoteTransfer $quoteTransfer): AvailableInternalPaymentAmountTransfer
    {
        $totalAmountOfEVoucher = (int)($quoteTransfer->getCustomer()->getCustomerBalance()->getAvailableCashbackAmount()->round(2)->toFloat() * 100);
        $priceToPay = $this->isBenefitVoucherSelected($quoteTransfer)
            ? $this->calculatePriceToPayWithBenefitVouchers($quoteTransfer)
            : $quoteTransfer->getTotals()->getGrandTotal();

        return (new AvailableInternalPaymentAmountTransfer())
            ->setAvailableEVoucherToCharge(
                $this->chooseAvailablePrice($priceToPay, $totalAmountOfEVoucher)
            );
    }

    /**
     * @param int $priceToPay
     * @param int $availableAmount
     *
     * @return int
     */
    protected function chooseAvailablePrice(int $priceToPay, int $availableAmount): int
    {
        return $priceToPay > $availableAmount ? $availableAmount : $priceToPay;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return int
     */
    protected function calculatePriceToPayWithBenefitVouchers(QuoteTransfer $quoteTransfer): int
    {
        $regularPriceWithOutSubtotal = $this->getRegularPriceWithoutSubTotal($quoteTransfer);
        $subtotalPriceWithBenefitVouchers = $this->getSubtotalPriceForBenefitVoucher($quoteTransfer);

        return $regularPriceWithOutSubtotal + $subtotalPriceWithBenefitVouchers;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return int
     */
    protected function getSubtotalPriceForBenefitVoucher(QuoteTransfer $quoteTransfer): int
    {
        $commonPrice = 0;

        foreach ($quoteTransfer->getItems() as $item) {
            if($item->getBenefitVoucherDealData() && $item->getUseBenefitVoucher()) {
                $commonPrice += (int)(100 * $item->getBenefitVoucherDealData()->getSalesPrice()) * $item->getQuantity();
            }
        }

        return $commonPrice;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return int
     */
    protected function getRegularPriceWithoutSubTotal(QuoteTransfer $quoteTransfer): int
    {
        $result = $quoteTransfer->getTotals()->getGrandTotal() - $quoteTransfer->getTotals()->getSubtotal();

        return $result >= 0 ? $result : 0;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function isBenefitVoucherSelected(QuoteTransfer $quoteTransfer): bool
    {
        foreach ($quoteTransfer->getItems() as $item) {
            if ($item->getUseBenefitVoucher()) {
                return true;
            }
        }

        return false;
    }
}
