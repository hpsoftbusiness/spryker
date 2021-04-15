<?php

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
        if ($quoteTransfer->getTotals() && $quoteTransfer->getTotals()->getPriceToPay() === 0 && !$quoteTransfer->getMyWorldUseEVoucherBalance()) {
            return $this->disallowRegularPaymentMethods($paymentMethodsTransfer);
        }

        return $this->disallowNoPaymentMethods($paymentMethodsTransfer);
    }
}
