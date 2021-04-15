<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\MyWorldPayment\Zed;

use Generated\Shared\Transfer\MyWorldApiRequestTransfer;
use Generated\Shared\Transfer\MyWorldApiResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface MyWorldPaymentStubInterface
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
    public function sendSmsCodeToCustomer(MyWorldApiRequestTransfer $myWorldApiRequestTransfer): MyWorldApiResponseTransfer;

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
}
