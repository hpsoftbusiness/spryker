<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MyWorldPayment\Business\RequestDispatcher;

use Generated\Shared\Transfer\MyWorldApiRequestTransfer;
use Generated\Shared\Transfer\MyWorldApiResponseTransfer;
use Generated\Shared\Transfer\PaymentDataResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface RequestDispatcherInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\MyWorldApiResponseTransfer
     */
    public function dispatchPaymentCreation(QuoteTransfer $quoteTransfer): MyWorldApiResponseTransfer;

    /**
     * @param \Generated\Shared\Transfer\MyWorldApiRequestTransfer $apiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\MyWorldApiResponseTransfer
     */
    public function dispatchSendSmsCodeToCustomer(MyWorldApiRequestTransfer $apiRequestTransfer): MyWorldApiResponseTransfer;

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\MyWorldApiResponseTransfer
     */
    public function dispatchSmsCodeRequestToCustomerByQuote(QuoteTransfer $quoteTransfer): MyWorldApiResponseTransfer;

    /**
     * @param \Generated\Shared\Transfer\MyWorldApiRequestTransfer $apiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\MyWorldApiResponseTransfer
     */
    public function dispatchValidateSmsCode(MyWorldApiRequestTransfer $apiRequestTransfer): MyWorldApiResponseTransfer;

    /**
     * @param \Generated\Shared\Transfer\MyWorldApiRequestTransfer $apiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\MyWorldApiResponseTransfer
     */
    public function dispatchConfirmPayment(MyWorldApiRequestTransfer $apiRequestTransfer): MyWorldApiResponseTransfer;

    /**
     * @param \Generated\Shared\Transfer\MyWorldApiRequestTransfer $apiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\MyWorldApiResponseTransfer
     */
    public function dispatchGetPayment(MyWorldApiRequestTransfer $apiRequestTransfer): MyWorldApiResponseTransfer;

    /**
     * @param \Generated\Shared\Transfer\PaymentDataResponseTransfer $paymentDataResponseTransfer
     * @param \Generated\Shared\Transfer\RefundTransfer[] $refundTransfers
     *
     * @return \Generated\Shared\Transfer\MyWorldApiResponseTransfer
     */
    public function dispatchRefundPayment(
        PaymentDataResponseTransfer $paymentDataResponseTransfer,
        array $refundTransfers
    ): MyWorldApiResponseTransfer;
}
