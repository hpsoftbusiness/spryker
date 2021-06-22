<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MyWorldPayment\Business\Generator;

use Generated\Shared\Transfer\MyWorldApiRequestTransfer;
use Generated\Shared\Transfer\PaymentDataResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface MyWorldPaymentRequestApiTransferGeneratorInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\MyWorldApiRequestTransfer
     */
    public function createPerformPaymentSessionCreationRequest(QuoteTransfer $quoteTransfer): MyWorldApiRequestTransfer;

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\MyWorldApiRequestTransfer
     */
    public function createPerformGenerateSmsCodeRequest(QuoteTransfer $quoteTransfer): MyWorldApiRequestTransfer;

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\MyWorldApiRequestTransfer
     */
    public function createSmsCodeValidationRequest(QuoteTransfer $quoteTransfer): MyWorldApiRequestTransfer;

    /**
     * @param \Generated\Shared\Transfer\PaymentDataResponseTransfer $paymentDataResponseTransfer
     * @param \Generated\Shared\Transfer\RefundTransfer[] $refundTransfers
     *
     * @return \Generated\Shared\Transfer\MyWorldApiRequestTransfer
     */
    public function createRefundRequest(
        PaymentDataResponseTransfer $paymentDataResponseTransfer,
        array $refundTransfers
    ): MyWorldApiRequestTransfer;
}
