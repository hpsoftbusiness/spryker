<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Refund\Business\Model\RefundCalculator;

use ArrayObject;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\RefundTransfer;
use Pyz\Zed\Refund\Business\Calculator\Item\ItemPaymentRefundCalculatorInterface;
use Spryker\Zed\Refund\Business\Model\RefundCalculator\ItemRefundCalculator as SprykerItemRefundCalculator;

class ItemRefundCalculator extends SprykerItemRefundCalculator
{
    /**
     * @var \Pyz\Zed\Refund\Business\Calculator\Item\ItemPaymentRefundCalculatorInterface
     */
    private $itemPaymentRefundCalculator;

    /**
     * @param \Pyz\Zed\Refund\Business\Calculator\Item\ItemPaymentRefundCalculatorInterface $itemPaymentRefundCalculator
     */
    public function __construct(ItemPaymentRefundCalculatorInterface $itemPaymentRefundCalculator)
    {
        $this->itemPaymentRefundCalculator = $itemPaymentRefundCalculator;
    }

    /**
     * @param \Generated\Shared\Transfer\RefundTransfer $refundTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem[] $salesOrderItems
     *
     * @return \Generated\Shared\Transfer\RefundTransfer
     */
    public function calculateRefund(
        RefundTransfer $refundTransfer,
        OrderTransfer $orderTransfer,
        array $salesOrderItems
    ): RefundTransfer {
        $refundTransfer = parent::calculateRefund($refundTransfer, $orderTransfer, $salesOrderItems);

        $this->calculateItemsRefundByPayment($refundTransfer->getItems(), $orderTransfer);

        return $refundTransfer;
    }

    /**
     * @param iterable|\Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    private function calculateItemsRefundByPayment(iterable $itemTransfers, OrderTransfer $orderTransfer): void
    {
        $paymentTransfers = $orderTransfer->getPayments()->getArrayCopy();
        foreach ($itemTransfers as $itemTransfer) {
            $this->itemPaymentRefundCalculator->calculateItemRefunds($itemTransfer, $paymentTransfers);
        }

        $orderTransfer->setPayments(new ArrayObject($paymentTransfers));
    }
}
