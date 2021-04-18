<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Payment\Business\Calculation;

use Generated\Shared\Transfer\CalculableObjectTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Pyz\Shared\MyWorldPayment\MyWorldPaymentConfig;
use Spryker\Zed\Payment\Business\Calculation\PaymentCalculator as SprykerPaymentCalculator;

class PaymentCalculator extends SprykerPaymentCalculator
{
    /**
     * @deprecated To be removed along with the single payment property.
     * When \Generated\Shared\Transfer\CalculableObjectTransfer::getPayments is used directly - override recalculatePayments
     * to filter out MyWorld payments.
     *
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentTransfer[]
     */
    protected function getPaymentCollection(CalculableObjectTransfer $calculableObjectTransfer)
    {
        $paymentCollection = parent::getPaymentCollection($calculableObjectTransfer);

        return $this->filterMyWorldPayments($paymentCollection);
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentTransfer[] $paymentCollection
     *
     * @return \Generated\Shared\Transfer\PaymentTransfer[]
     */
    private function filterMyWorldPayments(array $paymentCollection): array
    {
        return array_filter(
            $paymentCollection,
            static function (PaymentTransfer $paymentTransfer): bool {
                return $paymentTransfer->getPaymentProvider() !== MyWorldPaymentConfig::PAYMENT_PROVIDER_NAME_MY_WORLD;
            }
        );
    }
}
