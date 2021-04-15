<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MyWorldPayment\Business\Model\Generator;

use Generated\Shared\Transfer\MyWorldApiRequestTransfer;
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
}
