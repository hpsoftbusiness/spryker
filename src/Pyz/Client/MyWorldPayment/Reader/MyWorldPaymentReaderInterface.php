<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\MyWorldPayment\Reader;

use Generated\Shared\Transfer\QuoteTransfer;

interface MyWorldPaymentReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param int $paymentOptionId
     *
     * @return bool
     */
    public function assertInternalPaymentCoversPriceToPay(QuoteTransfer $quoteTransfer, int $paymentOptionId): bool;

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return int|null
     */
    public function findUsedInternalPaymentMethodOptionId(QuoteTransfer $quoteTransfer): ?int;
}
