<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Refund\Communication\Plugin;

use Generated\Shared\Transfer\RefundDetailTransfer;
use Pyz\Zed\Refund\Dependency\Plugin\RefundDetailsCollectorPluginInterface;

abstract class AbstractRefundDetailsCollectorPlugin implements RefundDetailsCollectorPluginInterface
{
    /**
     * @param iterable|\Generated\Shared\Transfer\ItemRefundTransfer[]|\Generated\Shared\Transfer\ExpenseRefundTransfer[] $refundTransfers
     * @param \Generated\Shared\Transfer\PaymentTransfer[] $mappedPayments
     *
     * @return \Generated\Shared\Transfer\RefundDetailTransfer[]
     */
    protected function mapRefundsToDetailTransfers(iterable $refundTransfers, array $mappedPayments): array
    {
        $refundDetailsCollection = [];

        foreach ($refundTransfers as $refundTransfer) {
            $refundDetailsTransfer = new RefundDetailTransfer();
            $refundDetailsTransfer->setAmount($refundTransfer->getAmount());
            $refundDetailsTransfer->setCreatedAt($refundTransfer->getCreatedAt());
            $refundDetailsTransfer->setStatus($refundTransfer->getStatus());
            if ($paymentTransfer = $mappedPayments[$refundTransfer->getFkSalesPayment()] ?? null) {
                $refundDetailsTransfer->setPaymentMethod($paymentTransfer->getPaymentMethod());
                $refundDetailsTransfer->setPaymentProvider($paymentTransfer->getPaymentProvider());
            }

            $refundDetailsCollection[] = $refundDetailsTransfer;
        }

        return $this->sortRefundsByDate($refundDetailsCollection);
    }

    /**
     * @param iterable|\Generated\Shared\Transfer\PaymentTransfer[] $paymentTransfers
     *
     * @return \Generated\Shared\Transfer\PaymentTransfer[]
     */
    protected function mapPaymentsById(iterable $paymentTransfers): array
    {
        $mappedPayments = [];

        foreach ($paymentTransfers as $paymentTransfer) {
            $mappedPayments[$paymentTransfer->getIdSalesPayment()] = $paymentTransfer;
        }

        return $mappedPayments;
    }

    /**
     * @param \Generated\Shared\Transfer\RefundDetailTransfer[] $refundDetailsTransfers
     *
     * @return \Generated\Shared\Transfer\RefundDetailTransfer[]
     */
    private function sortRefundsByDate(array $refundDetailsTransfers): array
    {
        usort(
            $refundDetailsTransfers,
            static function (RefundDetailTransfer $first, RefundDetailTransfer $second): int {
                if (!$first->getCreatedAt() || !$second->getCreatedAt()) {
                    return !$first->getCreatedAt() ? -1 : 1;
                }

                return strtotime($first->getCreatedAt()) - strtotime($second->getCreatedAt());
            }
        );

        return $refundDetailsTransfers;
    }
}
