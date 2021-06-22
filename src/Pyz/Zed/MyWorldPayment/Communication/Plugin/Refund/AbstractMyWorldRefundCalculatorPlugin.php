<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MyWorldPayment\Communication\Plugin\Refund;

use Generated\Shared\Transfer\PaymentTransfer;
use Pyz\Zed\Refund\Dependency\Plugin\ItemRefundCalculatorPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

abstract class AbstractMyWorldRefundCalculatorPlugin extends AbstractPlugin implements ItemRefundCalculatorPluginInterface
{
    protected const APPLICABLE_PAYMENT_METHOD = '';

    /**
     * @param \Generated\Shared\Transfer\PaymentTransfer[] $paymentTransfers
     *
     * @return \Generated\Shared\Transfer\PaymentTransfer|null
     */
    public function findApplicablePayment(array $paymentTransfers): ?PaymentTransfer
    {
        foreach ($paymentTransfers as $paymentTransfer) {
            if ($paymentTransfer->getPaymentMethod() === static::APPLICABLE_PAYMENT_METHOD) {
                return $paymentTransfer;
            }
        }

        return null;
    }
}
