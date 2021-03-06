<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Nopayment\Business\Nopayment;

use Generated\Shared\Transfer\PaymentMethodsTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Nopayment\Business\Nopayment\NopaymentMethodFilter as SpyNopaymentMethodFilter;

class NopaymentMethodFilter extends SpyNopaymentMethodFilter
{
    /**
     * @param \Generated\Shared\Transfer\PaymentMethodsTransfer $paymentMethodsTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentMethodsTransfer
     */
    public function filterPaymentMethods(PaymentMethodsTransfer $paymentMethodsTransfer, QuoteTransfer $quoteTransfer)
    {
        if ($quoteTransfer->getTotals()
            && $quoteTransfer->getTotals()->getPriceToPay() === 0
            && $this->assertMyWorldPaymentMethodSelected($quoteTransfer)
        ) {
            return $this->disallowRegularPaymentMethods($paymentMethodsTransfer);
        }

        return $this->disallowNoPaymentMethods($paymentMethodsTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    private function assertMyWorldPaymentMethodSelected(QuoteTransfer $quoteTransfer): bool
    {
        return $quoteTransfer->getUseEVoucherBalance()
            || $quoteTransfer->getUseEVoucherOnBehalfOfMarketer()
            || $quoteTransfer->getUseCashbackBalance();
    }
}
