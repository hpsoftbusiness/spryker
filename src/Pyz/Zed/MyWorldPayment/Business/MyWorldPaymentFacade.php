<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MyWorldPayment\Business;

use Generated\Shared\Transfer\AvailableInternalPaymentAmountTransfer;
use Generated\Shared\Transfer\CalculableObjectTransfer;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\MyWorldApiRequestTransfer;
use Generated\Shared\Transfer\MyWorldApiResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Pyz\Zed\MyWorldPayment\Business\MyWorldPaymentBusinessFactory getFactory()
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
    public function sendSmsCodeToCustomer(MyWorldApiRequestTransfer $myWorldApiRequestTransfer): MyWorldApiResponseTransfer
    {
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
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $quoteTransfer
     *
     * @return void
     */
    public function recalculatePricesForQuote(CalculableObjectTransfer $quoteTransfer): void
    {
        $this->getFactory()
            ->createEVoucherPaymentCalculator()
            ->recalculateQuote($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    public function recalculatePricesForOrder(CalculableObjectTransfer $calculableObjectTransfer): void
    {
        $this->getFactory()
            ->createEVoucherPaymentCalculator()
            ->recalculateOrder($calculableObjectTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    public function recalculateItemsPricesForBenefitVoucherOrder(CalculableObjectTransfer $calculableObjectTransfer): void
    {
        $this->getFactory()
            ->createBenefitVoucherPaymentCalculator()
            ->recalculateOrder($calculableObjectTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    public function recalculateItemsPricesForBenefitVoucherQuote(CalculableObjectTransfer $calculableObjectTransfer): void
    {
        $this->getFactory()
            ->createBenefitVoucherPaymentCalculator()
            ->recalculateQuote($calculableObjectTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\AvailableInternalPaymentAmountTransfer
     */
    public function calculateAvailablePricesForInternalPayments(QuoteTransfer $quoteTransfer): AvailableInternalPaymentAmountTransfer
    {
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
}
