<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Refund\Business\Calculator\Payment;

use Generated\Shared\Transfer\ItemRefundTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Pyz\Zed\Refund\Persistence\RefundRepositoryInterface;

class RefundablePaymentCalculator implements RefundablePaymentCalculatorInterface
{
    /**
     * @var \Pyz\Zed\Refund\Persistence\RefundRepositoryInterface
     */
    private $repository;

    /**
     * @param \Pyz\Zed\Refund\Persistence\RefundRepositoryInterface $repository
     */
    public function __construct(RefundRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function calculateOrderPaymentsRefundableAmount(OrderTransfer $orderTransfer): OrderTransfer
    {
        $itemRefundTransfers = $this->repository->findOrderItemRefundsByIdSalesOrder($orderTransfer->getIdSalesOrder());
        $groupedItemRefunds = $this->groupItemRefundsByPaymentId($itemRefundTransfers);
        foreach ($orderTransfer->getPayments() as $paymentTransfer) {
            $paymentItemRefunds = $groupedItemRefunds[$paymentTransfer->getIdSalesPayment()] ?? null;
            if (!$paymentItemRefunds) {
                $paymentTransfer->setRefundableAmount($paymentTransfer->getAmount());

                continue;
            }

            $this->calculateRefundableAmount($paymentTransfer, $paymentItemRefunds);
        }

        return $orderTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentTransfer $paymentTransfer
     * @param \Generated\Shared\Transfer\ItemRefundTransfer[] $itemRefunds
     *
     * @return void
     */
    private function calculateRefundableAmount(PaymentTransfer $paymentTransfer, array $itemRefunds): void
    {
        $refundedAmount = array_reduce(
            $itemRefunds,
            function (int $amount, ItemRefundTransfer $itemRefundTransfer): int {
                return $amount + $itemRefundTransfer->getAmount();
            },
            0
        );

        $paymentTransfer->setRefundableAmount($paymentTransfer->getAmount() - $refundedAmount);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemRefundTransfer[] $itemRefundTransfers
     *
     * @return array
     */
    private function groupItemRefundsByPaymentId(array $itemRefundTransfers): array
    {
        $groupedItemRefunds = [];
        foreach ($itemRefundTransfers as $itemRefundTransfer) {
            $groupedItemRefunds[$itemRefundTransfer->getFkSalesPayment()][] = $itemRefundTransfer;
        }

        return $groupedItemRefunds;
    }
}
