<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Refund\Business\Processor\Aggregator;

use Generated\Shared\Transfer\ItemRefundTransfer;
use Generated\Shared\Transfer\RefundTransfer;
use Pyz\Zed\Refund\Business\Exception\RefundProcessingException;
use Pyz\Zed\Refund\RefundConfig;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;

class PaymentRefundsAggregator implements PaymentRefundsAggregatorInterface
{
    private const EXCEPTION_PAYMENT_METHOD_NOT_USED = 'Refund payment method not used by the order.';

    /**
     * @param \Generated\Shared\Transfer\ItemRefundTransfer[] $itemRefunds
     * @param \Generated\Shared\Transfer\ExpenseRefundTransfer[] $expenseRefunds
     * @param \Generated\Shared\Transfer\PaymentTransfer[] $payments
     *
     * @return \Generated\Shared\Transfer\RefundTransfer[]
     */
    public function aggregate(array $itemRefunds, array $expenseRefunds, array $payments): array
    {
        $mappedPaymentTransfers = $this->mapPaymentsById($payments);
        $mappedAggregatedRefunds = $this->aggregateRefundsByPaymentIds($itemRefunds, $expenseRefunds);
        $this->assignRefundPayments($mappedAggregatedRefunds, $mappedPaymentTransfers);

        return $mappedAggregatedRefunds;
    }

    /**
     * @param \Generated\Shared\Transfer\RefundTransfer[] $mappedRefunds
     * @param \Generated\Shared\Transfer\PaymentTransfer[] $mappedPaymentTransfers
     *
     * @throws \Pyz\Zed\Refund\Business\Exception\RefundProcessingException
     *
     * @return void
     */
    private function assignRefundPayments(array $mappedRefunds, array $mappedPaymentTransfers): void
    {
        foreach ($mappedRefunds as $refundTransfer) {
            $paymentTransfer = $mappedPaymentTransfers[$refundTransfer->getFkSalesPayment()] ?? null;
            if (!$paymentTransfer) {
                throw new RefundProcessingException(self::EXCEPTION_PAYMENT_METHOD_NOT_USED);
            }

            $refundTransfer->setPayment($paymentTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ItemRefundTransfer[] $itemRefunds
     * @param \Generated\Shared\Transfer\ExpenseRefundTransfer[] $expensesRefunds
     *
     * @return \Generated\Shared\Transfer\RefundTransfer[]
     */
    private function aggregateRefundsByPaymentIds(array $itemRefunds, array $expensesRefunds): array
    {
        $refundTransfers = $this->aggregateItemRefunds($itemRefunds, []);
        $refundTransfers = $this->aggregateItemRefunds($expensesRefunds, $refundTransfers);

        return $refundTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemRefundTransfer[]|\Generated\Shared\Transfer\ExpenseRefundTransfer[] $itemRefunds
     * @param \Generated\Shared\Transfer\RefundTransfer[] $refundTransfers
     *
     * @return \Generated\Shared\Transfer\RefundTransfer[]
     */
    private function aggregateItemRefunds(array $itemRefunds, array $refundTransfers): array
    {
        foreach ($itemRefunds as $itemRefundTransfer) {
            if ($itemRefundTransfer->getStatus() === RefundConfig::PAYMENT_REFUND_STATUS_PROCESSED) {
                continue;
            }

            $idSalesPayment = $itemRefundTransfer->getFkSalesPayment();
            if (!isset($refundTransfers[$idSalesPayment])) {
                $refundTransfers[$idSalesPayment] = $this->createRefundTransfer($idSalesPayment);
            }

            $refundTransfer = $refundTransfers[$idSalesPayment];
            $refundTransfer->setAmount((int)$refundTransfer->getAmount() + (int)$itemRefundTransfer->getAmount());
            $this->appendItemRefund($refundTransfer, $itemRefundTransfer);
        }

        return $refundTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\RefundTransfer $refundTransfer
     * @param \Generated\Shared\Transfer\ItemRefundTransfer|\Generated\Shared\Transfer\ExpenseRefundTransfer $itemRefundTransfer
     *
     * @return void
     */
    private function appendItemRefund(RefundTransfer $refundTransfer, AbstractTransfer $itemRefundTransfer): void
    {
        if ($itemRefundTransfer instanceof ItemRefundTransfer) {
            $refundTransfer->addItemRefund($itemRefundTransfer);
        } else {
            $refundTransfer->addExpenseRefund($itemRefundTransfer);
        }
    }

    /**
     * @param int $idSalesPayment
     *
     * @return \Generated\Shared\Transfer\RefundTransfer
     */
    private function createRefundTransfer(int $idSalesPayment): RefundTransfer
    {
        return (new RefundTransfer())->setFkSalesPayment($idSalesPayment);
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentTransfer[] $paymentTransfers
     *
     * @return \Generated\Shared\Transfer\PaymentTransfer[]
     */
    private function mapPaymentsById(array $paymentTransfers): array
    {
        $mappedPayments = [];
        foreach ($paymentTransfers as $paymentTransfer) {
            $mappedPayments[$paymentTransfer->getIdSalesPayment()] = $paymentTransfer;
        }

        return $mappedPayments;
    }
}
