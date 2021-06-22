<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Shared\Refund\Calculator;

use Generated\Shared\Transfer\ItemRefundTransfer;
use Pyz\Zed\Refund\RefundConfig;

trait ItemRefundCalculatorTrait
{
    /**
     * @param int $idSalesOrderItem
     * @param int $idSalesPayment
     * @param int $refundAmount
     *
     * @return \Generated\Shared\Transfer\ItemRefundTransfer
     */
    private function createItemRefundTransfer(
        int $idSalesOrderItem,
        int $idSalesPayment,
        int $refundAmount
    ): ItemRefundTransfer {
        $itemRefundTransfer = new ItemRefundTransfer();
        $itemRefundTransfer->setFkSalesOrderItem($idSalesOrderItem);
        $itemRefundTransfer->setFkSalesPayment($idSalesPayment);
        $itemRefundTransfer->setAmount($refundAmount);
        $itemRefundTransfer->setStatus(RefundConfig::PAYMENT_REFUND_STATUS_NEW);

        return $itemRefundTransfer;
    }
}
