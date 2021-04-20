<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MyWorldPayment\Business\PaymentPriceManager;

use Generated\Shared\Transfer\AvailableInternalPaymentAmountTransfer;
use Generated\Shared\Transfer\CalculableObjectTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface PaymentPriceManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\AvailableInternalPaymentAmountTransfer
     */
    public function getAvailablePriceToPay(QuoteTransfer $quoteTransfer): AvailableInternalPaymentAmountTransfer;

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return \Generated\Shared\Transfer\AvailableInternalPaymentAmountTransfer
     */
    public function getAvailablePriceToPayByCalculableObject(CalculableObjectTransfer $calculableObjectTransfer): AvailableInternalPaymentAmountTransfer;
}
