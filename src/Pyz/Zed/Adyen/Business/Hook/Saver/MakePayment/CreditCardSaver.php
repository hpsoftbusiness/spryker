<?php
declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Adyen\Business\Hook\Saver\MakePayment;

use Generated\Shared\Transfer\PaymentAdyenTransfer;
use SprykerEco\Zed\Adyen\Business\Hook\Saver\MakePayment\CreditCardSaver as SprykerEcoCreditCardSaver;

/**
 * @property \Pyz\Zed\Adyen\AdyenConfig $config
 */
class CreditCardSaver extends SprykerEcoCreditCardSaver
{
    /**
     * @param \Generated\Shared\Transfer\PaymentAdyenTransfer|null $paymentAdyenTransfer
     *
     * @return string
     */
    protected function getPaymentStatus(?PaymentAdyenTransfer $paymentAdyenTransfer = null): string
    {
        if (!$paymentAdyenTransfer) {
            return $this->config->getOmsStatusCanceled();
        }

        $paymentStatus = $this->config->getOmsStatusNew();
        switch ($paymentAdyenTransfer->getResultCode()) {
            case $this->config->getAdyenResultCodeAuthorised():
                $paymentStatus = $this->config->getOmsStatusAuthorized();
                break;

            case $this->config->getAdyenResultCodeRefused():
            case $this->config->getAdyenResultCodeError():
                $paymentStatus = $this->config->getOmsStatusRefused();
                break;
        }

        return $paymentStatus;
    }
}
