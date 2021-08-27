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
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Pyz\Zed\MyWorldPayment\Business\MyWorldPaymentBusinessFactory getFactory()
 * @method \Pyz\Zed\MyWorldPayment\Persistence\MyWorldPaymentEntityManagerInterface getEntityManager()
 * @method \Pyz\Zed\MyWorldPayment\Persistence\MyWorldPaymentRepositoryInterface getRepository()
 */
class MyWorldPaymentFacade extends AbstractFacade implements MyWorldPaymentFacadeInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\MyWorldApiResponseTransfer
     */
    public function createPaymentSession(QuoteTransfer $quoteTransfer): MyWorldApiResponseTransfer
    {
        return $this->getFactory()
            ->createMyWorldPaymentApiRequestDispatcher()
            ->dispatchPaymentCreation($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\MyWorldApiRequestTransfer $myWorldApiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\MyWorldApiResponseTransfer
     */
    public function sendSmsCodeToCustomer(
        MyWorldApiRequestTransfer $myWorldApiRequestTransfer
    ): MyWorldApiResponseTransfer {
        return $this->getFactory()
            ->createMyWorldPaymentApiRequestDispatcher()
            ->dispatchSendSmsCodeToCustomer($myWorldApiRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\MyWorldApiRequestTransfer $myWorldApiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\MyWorldApiResponseTransfer
     */
    public function validateSmsCode(MyWorldApiRequestTransfer $myWorldApiRequestTransfer): MyWorldApiResponseTransfer
    {
        return $this->getFactory()
            ->createMyWorldPaymentApiRequestDispatcher()
            ->dispatchValidateSmsCode($myWorldApiRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\MyWorldApiRequestTransfer $myWorldApiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\MyWorldApiResponseTransfer
     */
    public function confirmPayment(MyWorldApiRequestTransfer $myWorldApiRequestTransfer): MyWorldApiResponseTransfer
    {
        return $this->getFactory()
            ->createMyWorldPaymentApiRequestDispatcher()
            ->dispatchConfirmPayment($myWorldApiRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\MyWorldApiRequestTransfer $myWorldApiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\MyWorldApiResponseTransfer
     */
    public function getPayment(MyWorldApiRequestTransfer $myWorldApiRequestTransfer): MyWorldApiResponseTransfer
    {
        return $this->getFactory()
            ->createMyWorldPaymentApiRequestDispatcher()
            ->dispatchGetPayment($myWorldApiRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentDataResponseTransfer $paymentDataResponseTransfer
     * @param int $idSalesOrder
     *
     * @return void
     */
    public function saveMyWorldPaymentData(
        PaymentDataResponseTransfer $paymentDataResponseTransfer,
        int $idSalesOrder
    ): void {
        $this->getEntityManager()->saveMyWorldPayment($paymentDataResponseTransfer, $idSalesOrder);
    }

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $quoteTransfer
     *
     * @return void
     */
    public function recalculateEVoucherPaymentForQuote(CalculableObjectTransfer $quoteTransfer): void
    {
        $this->getFactory()
            ->createEVoucherPaymentCalculator()
            ->recalculateQuote($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $quoteTransfer
     *
     * @return void
     */
    public function recalculateEVoucherMarketerPaymentForQuote(CalculableObjectTransfer $quoteTransfer): void
    {
        $this->getFactory()
            ->createEVoucherMarketerPaymentCalculator()
            ->recalculateQuote($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $quoteTransfer
     *
     * @return void
     */
    public function recalculateCashbackPaymentForQuote(CalculableObjectTransfer $quoteTransfer): void
    {
        $this->getFactory()
            ->createCashbackPaymentCalculator()
            ->recalculateQuote($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    public function recalculateItemsPricesForBenefitVoucherQuote(
        CalculableObjectTransfer $calculableObjectTransfer
    ): void {
        $this->getFactory()
            ->createBenefitVoucherPaymentCalculator()
            ->recalculateQuote($calculableObjectTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    public function recalculateItemsPricesForBenefitVoucherOrder(
        CalculableObjectTransfer $calculableObjectTransfer
    ): void {
        $this->getFactory()
            ->createBenefitVoucherPaymentCalculator()
            ->recalculateOrder($calculableObjectTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\AvailableInternalPaymentAmountTransfer
     */
    public function calculateAvailablePricesForInternalPayments(
        QuoteTransfer $quoteTransfer
    ): AvailableInternalPaymentAmountTransfer {
        return $this->getFactory()
            ->createPaymentPriceManager()
            ->getAvailablePriceToPay($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    public function recalculateQuoteShoppingPoints(CalculableObjectTransfer $calculableObjectTransfer): void
    {
        $this->getFactory()->createShoppingPointsPaymentCalculator()->recalculateQuote($calculableObjectTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    public function recalculateOrderShoppingPoints(CalculableObjectTransfer $calculableObjectTransfer): void
    {
        $this->getFactory()->createShoppingPointsPaymentCalculator()->recalculateOrder($calculableObjectTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    public function recalculateQuoteTurnover(CalculableObjectTransfer $calculableObjectTransfer): void
    {
        $this
            ->getFactory()
            ->createTurnoverCalculator()
            ->recalculateQuote($calculableObjectTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    public function recalculateQuoteSubtotalWithDeals(CalculableObjectTransfer $calculableObjectTransfer): void
    {
        $this->getFactory()->createSubtotalWithDealsCalculator()->recalculateQuote($calculableObjectTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    public function recalculateQuoteGrandTotalWithDeals(CalculableObjectTransfer $calculableObjectTransfer): void
    {
        $this->getFactory()->createGrandTotalWithDealsCalculator()->recalculateQuote($calculableObjectTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    public function recalculateOrderTurnover(CalculableObjectTransfer $calculableObjectTransfer): void
    {
        $this
            ->getFactory()
            ->createTurnoverCalculator()
            ->recalculateOrder($calculableObjectTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    public function recalculateQuoteSegmentNumber(CalculableObjectTransfer $calculableObjectTransfer): void
    {
        $this
            ->getFactory()
            ->createSegmentNumberCalculator()
            ->recalculateQuote($calculableObjectTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    public function recalculateOrderSegmentNumber(CalculableObjectTransfer $calculableObjectTransfer): void
    {
        $this
            ->getFactory()
            ->createSegmentNumberCalculator()
            ->recalculateOrder($calculableObjectTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\RefundTransfer[] $refundTransfers
     *
     * @return void
     */
    public function processRefunds(array $refundTransfers): void
    {
        $this->getFactory()->createRefundProcessor()->processRefunds($refundTransfers);
    }
}
