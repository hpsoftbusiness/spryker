<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MyWorldPayment\Business;

use Generated\Shared\Transfer\AvailableInternalPaymentAmountTransfer;
use Generated\Shared\Transfer\CalculableObjectTransfer;
use Generated\Shared\Transfer\MyWorldApiRequestTransfer;
use Generated\Shared\Transfer\MyWorldApiResponseTransfer;
use Generated\Shared\Transfer\PaymentDataResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface MyWorldPaymentFacadeInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\MyWorldApiResponseTransfer
     */
    public function createPaymentSession(QuoteTransfer $quoteTransfer): MyWorldApiResponseTransfer;

    /**
     * @param \Generated\Shared\Transfer\MyWorldApiRequestTransfer $myWorldApiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\MyWorldApiResponseTransfer
     */
    public function sendSmsCodeToCustomer(
        MyWorldApiRequestTransfer $myWorldApiRequestTransfer
    ): MyWorldApiResponseTransfer;

    /**
     * @param \Generated\Shared\Transfer\MyWorldApiRequestTransfer $myWorldApiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\MyWorldApiResponseTransfer
     */
    public function validateSmsCode(MyWorldApiRequestTransfer $myWorldApiRequestTransfer): MyWorldApiResponseTransfer;

    /**
     * @param \Generated\Shared\Transfer\MyWorldApiRequestTransfer $myWorldApiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\MyWorldApiResponseTransfer
     */
    public function confirmPayment(MyWorldApiRequestTransfer $myWorldApiRequestTransfer): MyWorldApiResponseTransfer;

    /**
     * @param \Generated\Shared\Transfer\MyWorldApiRequestTransfer $myWorldApiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\MyWorldApiResponseTransfer
     */
    public function getPayment(MyWorldApiRequestTransfer $myWorldApiRequestTransfer): MyWorldApiResponseTransfer;

    /**
     * @param \Generated\Shared\Transfer\PaymentDataResponseTransfer $paymentDataResponseTransfer
     * @param int $idSalesOrder
     *
     * @return void
     */
    public function saveMyWorldPaymentData(
        PaymentDataResponseTransfer $paymentDataResponseTransfer,
        int $idSalesOrder
    ): void;

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $quoteTransfer
     *
     * @return void
     */
    public function recalculateEVoucherPaymentForQuote(CalculableObjectTransfer $quoteTransfer): void;

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    public function recalculateItemsPricesForBenefitVoucherQuote(
        CalculableObjectTransfer $calculableObjectTransfer
    ): void;

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    public function recalculateItemsPricesForBenefitVoucherOrder(
        CalculableObjectTransfer $calculableObjectTransfer
    ): void;

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $quoteTransfer
     *
     * @return void
     */
    public function recalculateEVoucherMarketerPaymentForQuote(CalculableObjectTransfer $quoteTransfer): void;

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $quoteTransfer
     *
     * @return void
     */
    public function recalculateCashbackPaymentForQuote(CalculableObjectTransfer $quoteTransfer): void;

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\AvailableInternalPaymentAmountTransfer
     */
    public function calculateAvailablePricesForInternalPayments(
        QuoteTransfer $quoteTransfer
    ): AvailableInternalPaymentAmountTransfer;

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    public function recalculateQuoteShoppingPoints(CalculableObjectTransfer $calculableObjectTransfer): void;

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    public function recalculateOrderShoppingPoints(CalculableObjectTransfer $calculableObjectTransfer): void;

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    public function recalculateQuoteTurnover(CalculableObjectTransfer $calculableObjectTransfer): void;

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    public function recalculateQuoteSubtotalWithDeals(CalculableObjectTransfer $calculableObjectTransfer): void;

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    public function recalculateQuoteGrandTotalWithDeals(CalculableObjectTransfer $calculableObjectTransfer): void;

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    public function recalculateOrderTurnover(CalculableObjectTransfer $calculableObjectTransfer): void;

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    public function recalculateQuoteSegmentNumber(CalculableObjectTransfer $calculableObjectTransfer): void;

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    public function recalculateOrderSegmentNumber(CalculableObjectTransfer $calculableObjectTransfer): void;

    /**
     * @param \Generated\Shared\Transfer\RefundTransfer[] $refundTransfers
     *
     * @return void
     */
    public function processRefunds(array $refundTransfers): void;
}
