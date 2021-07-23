<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\CheckoutPage\Process\Steps\SummaryStep;

use Generated\Shared\Transfer\QuoteTransfer;
use Pyz\Shared\MyWorldPayment\MyWorldPaymentConfig;
use Pyz\Yves\CheckoutPage\Process\Steps\PreConditionCheckerInterface;

class PreConditionChecker implements PreConditionCheckerInterface
{
    protected const KEY_MESSAGE_SMS_CODE_SEND_ERROR = 'checkout.step.summary.sms_code_send_error';

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function check(QuoteTransfer $quoteTransfer): bool
    {
        $quoteTransfer->setSmsCode(null);

        return $this->wasPaymentSessionCreatedSuccessfully($quoteTransfer) || !$this->isMyWorldPaymentUsed(
            $quoteTransfer
        );
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function wasPaymentSessionCreatedSuccessfully(QuoteTransfer $quoteTransfer): bool
    {
        return $this->isMyWorldPaymentUsed($quoteTransfer)
            && $quoteTransfer->getMyWorldPaymentSessionId();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function isMyWorldPaymentUsed(QuoteTransfer $quoteTransfer): bool
    {
        foreach ($quoteTransfer->getPayments() as $payment) {
            if ($payment->getPaymentProvider() === MyWorldPaymentConfig::PAYMENT_PROVIDER_NAME_MY_WORLD) {
                return true;
            }
        }

        return false;
    }
}
