<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Adyen\Business;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\RefundTransfer;
use SprykerEco\Zed\Adyen\Business\AdyenFacadeInterface as SprykerEcoAdyenFacadeInterface;

interface AdyenFacadeInterface extends SprykerEcoAdyenFacadeInterface
{
    /**
     * Specification:
     * - Expands order with adyen payment transfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function expandOrderWithAdyenPayment(OrderTransfer $orderTransfer): OrderTransfer;

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem[] $orderItems
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\RefundTransfer $refundTransfer
     *
     * @return void
     */
    public function executeRefundCommand(
        array $orderItems,
        OrderTransfer $orderTransfer,
        RefundTransfer $refundTransfer
    ): void;
}
