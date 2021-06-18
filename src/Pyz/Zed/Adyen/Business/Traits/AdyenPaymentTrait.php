<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Adyen\Business\Traits;

use Generated\Shared\Transfer\PaymentTransfer;
use Pyz\Shared\Adyen\AdyenConfig as SharedAdyenConfig;
use SprykerEco\Zed\Adyen\Business\Exception\AdyenMethodMapperException;

trait AdyenPaymentTrait
{
    /**
     * @param \Generated\Shared\Transfer\PaymentTransfer[]|iterable $paymentTransfers
     *
     * @throws \SprykerEco\Zed\Adyen\Business\Exception\AdyenMethodMapperException
     *
     * @return \Generated\Shared\Transfer\PaymentTransfer
     */
    private function getAdyenPaymentTransfer(iterable $paymentTransfers): PaymentTransfer
    {
        foreach ($paymentTransfers as $paymentTransfer) {
            if ($paymentTransfer->getPaymentProvider() === SharedAdyenConfig::PROVIDER_NAME) {
                return $paymentTransfer;
            }
        }

        throw new AdyenMethodMapperException('Adyen payment not found.');
    }
}
