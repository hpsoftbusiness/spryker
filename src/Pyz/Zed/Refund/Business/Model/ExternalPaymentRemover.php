<?php
declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Refund\Business\Model;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\RefundTransfer;
use Pyz\Shared\MyWorldPayment\MyWorldPaymentConfig;
use Pyz\Zed\Refund\RefundConfig;
use Pyz\Zed\Sales\Business\SalesFacadeInterface;

class ExternalPaymentRemover implements ExternalPaymentRemoverInterface
{
    /**
     * @var \Pyz\Zed\Sales\Business\SalesFacadeInterface
     */
    protected $salesFacade;

    /**
     * @param \Pyz\Zed\Sales\Business\SalesFacadeInterface $salesFacade
     */
    public function __construct(SalesFacadeInterface $salesFacade)
    {
        $this->salesFacade = $salesFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\RefundTransfer $refundTransfer
     * @param int $idSalesOrder
     *
     * @return void
     */
    public function removeExternalPayments(RefundTransfer $refundTransfer, int $idSalesOrder): void
    {
        $orderTransfer = $this
            ->salesFacade
            ->getOrderByIdSalesOrder($idSalesOrder);

        $externalPayments = $this->getExternalPayments($orderTransfer);

        foreach ($refundTransfer->getItems() as $item) {
            $refunds = $item->getRefunds();
            foreach ($refunds as $index => $refund) {
                $idSalesPayment = $refund->getFkSalesPayment();
                if ($this->isExternalPayment($idSalesPayment, $externalPayments)) {
                    $refunds->offsetUnset($index);
                }
            }
        }

        foreach ($refundTransfer->getExpenses() as $expense) {
            $expenseRefunds = $expense->getRefunds();
            foreach ($expenseRefunds as $index => $expenseRefund) {
                $idSalesPayment = $expenseRefund->getFkSalesPayment();
                if ($this->isExternalPayment($idSalesPayment, $externalPayments)) {
                    $expenseRefunds->offsetUnset($index);
                }
            }
        }

        $this->fixRefundAmount($refundTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentTransfer[]
     */
    private function getExternalPayments(OrderTransfer $orderTransfer): array
    {
        $externalPayments = [];
        $payments = $orderTransfer->getPayments();
        foreach ($payments as $payment) {
            if ($payment->getPaymentProvider() !== MyWorldPaymentConfig::PAYMENT_PROVIDER_NAME_MY_WORLD) {
                $externalPayments[$payment->getIdSalesPayment()] = $payment;
            }
        }

        return $externalPayments;
    }

    /**
     * @param int $idSalesPayment
     * @param \Generated\Shared\Transfer\PaymentTransfer[] $externalPayments
     *
     * @return bool
     */
    private function isExternalPayment(int $idSalesPayment, array $externalPayments): bool
    {
        return isset($externalPayments[$idSalesPayment]);
    }

    /**
     * @param \Generated\Shared\Transfer\RefundTransfer $refundTransfer
     *
     * @return void
     */
    private function fixRefundAmount(RefundTransfer $refundTransfer)
    {
        $refundAmount = 0;
        foreach ($refundTransfer->getItems() as $item) {
            foreach ($item->getRefunds() as $itemRefund) {
                if ($itemRefund->getStatus() === RefundConfig::PAYMENT_REFUND_STATUS_PROCESSED) {
                    continue;
                }
                $refundAmount += $itemRefund->getAmount();
            }
        }

        foreach ($refundTransfer->getExpenses() as $expense) {
            foreach ($expense->getRefunds() as $expenseRefund) {
                if ($expenseRefund->getStatus() === RefundConfig::PAYMENT_REFUND_STATUS_PROCESSED) {
                    continue;
                }
                $refundAmount += $expenseRefund->getAmount();
            }
        }
        $refundTransfer->setAmount($refundAmount);
    }
}
