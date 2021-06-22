<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Refund\Business;

use Generated\Shared\Transfer\ExpenseRefundTransfer;
use Generated\Shared\Transfer\ItemRefundTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Spryker\Zed\Refund\Business\RefundFacadeInterface as SprykerRefundFacadeInterface;

interface RefundFacadeInterface extends SprykerRefundFacadeInterface
{
    /**
     * Specification:
     * - Collects refunds for each order item and expense ordered by created date.
     *
     * @param int $idSalesOrder
     *
     * @return \Generated\Shared\Transfer\RefundDetailCollectionTransfer[]
     */
    public function getRefundDetailsByIdSalesOrder(int $idSalesOrder): array;

    /**
     * Specification:
     * - Collects calculated item and expense refunds.
     * - Executes processor plugins for aggregated refund groups.
     * - Updates item and expense refund statuses.
     *
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem[] $orderItemEntities
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     *
     * @return void
     */
    public function processRefund(array $orderItemEntities, SpySalesOrder $orderEntity): void;

    /**
     * Specification:
     * - Collects pending item and expense refunds.
     * - Executes validator plugins by refund payment provider.
     * - Updates item and expense refund statuses.
     *
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem[] $orderItemEntities
     * @param int $idSalesOrder
     *
     * @return void
     */
    public function validateRefund(array $orderItemEntities, int $idSalesOrder): void;

    /**
     * Specification:
     * - Persists sales order item refund to database.
     *
     * @param \Generated\Shared\Transfer\ItemRefundTransfer $itemRefundTransfer
     *
     * @return void
     */
    public function saveOrderItemRefund(ItemRefundTransfer $itemRefundTransfer): void;

    /**
     * Specification:
     * - Persists sales order expense refund to database.
     *
     * @param \Generated\Shared\Transfer\ExpenseRefundTransfer $expenseRefundTransfer
     *
     * @return void
     */
    public function saveOrderExpenseRefund(ExpenseRefundTransfer $expenseRefundTransfer): void;
}
