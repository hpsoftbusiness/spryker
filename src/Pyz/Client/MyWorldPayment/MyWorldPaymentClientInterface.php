<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\MyWorldPayment;

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
     * @param \Generated\Shared\Transfer\MyWorldApiRequestTransfer $myWorldApiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\MyWorldApiResponseTransfer
     */
    public function sendSmsCodeToCustomer(MyWorldApiRequestTransfer $myWorldApiRequestTransfer): MyWorldApiResponseTransfer;

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
}
