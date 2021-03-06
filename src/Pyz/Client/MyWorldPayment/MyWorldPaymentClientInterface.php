<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\MyWorldPayment;

use Generated\Shared\Transfer\AvailableInternalPaymentAmountTransfer;
use Generated\Shared\Transfer\MyWorldApiRequestTransfer;
use Generated\Shared\Transfer\MyWorldApiResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface MyWorldPaymentClientInterface
{
    /**
     * Specification:
     *  - Generate request transfer for Cashback Service for request
     *  - Send request to Cashback Service for create session
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\MyWorldApiResponseTransfer
     */
    public function createPaymentSession(QuoteTransfer $quoteTransfer): MyWorldApiResponseTransfer;

    /**
     * Specification:
     *  - Send request to Cashback Service for send sms code to client by the session id
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\MyWorldApiResponseTransfer
     */
    public function sendSmsCodeToCustomerByQuote(QuoteTransfer $quoteTransfer): MyWorldApiResponseTransfer;

    /**
     * Specification:
     *  - Send request to Cashback Service for validate provided sms code
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MyWorldApiRequestTransfer $myWorldApiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\MyWorldApiResponseTransfer
     */
    public function validateSmsCode(MyWorldApiRequestTransfer $myWorldApiRequestTransfer): MyWorldApiResponseTransfer;

    /**
     * Specification:
     *  - Send request to Cashback Service for confirm payment by provided session id
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MyWorldApiRequestTransfer $myWorldApiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\MyWorldApiResponseTransfer
     */
    public function confirmPayment(MyWorldApiRequestTransfer $myWorldApiRequestTransfer): MyWorldApiResponseTransfer;

    /**
     * Specification:
     *  - Send request to Cashback Service for fetch the payment by provided session id
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MyWorldApiRequestTransfer $myWorldApiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\MyWorldApiResponseTransfer
     */
    public function getPayment(MyWorldApiRequestTransfer $myWorldApiRequestTransfer): MyWorldApiResponseTransfer;

    /**
     * Specification:
     * - Calculate available price by currently selected internal payments at the quota
     * - Return transfer with available prices
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\AvailableInternalPaymentAmountTransfer
     */
    public function getAvailableInternalPaymentPrices(QuoteTransfer $quoteTransfer): AvailableInternalPaymentAmountTransfer;

    /**
     * Specification:
     * check if internal payments methods cover product in cart
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param int $paymentOptionId
     *
     * @return bool
     */
    public function assertInternalPaymentCoversPriceToPay(QuoteTransfer $quoteTransfer, int $paymentOptionId): bool;

    /**
     * Specification:
     * get internal payment method ids
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return int|null
     */
    public function findUsedInternalPaymentMethodOptionId(QuoteTransfer $quoteTransfer): ?int;
}
