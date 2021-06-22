<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Refund\Persistence;

interface RefundRepositoryInterface
{
    /**
     * @param int $idSalesOrder
     *
     * @return \Generated\Shared\Transfer\ItemRefundTransfer[]
     */
    public function findOrderItemRefundsByIdSalesOrder(int $idSalesOrder): array;

    /**
     * @param int $idSalesOrder
     *
     * @return \Generated\Shared\Transfer\ExpenseRefundTransfer[]
     */
    public function findOrderExpenseRefundsByIdSalesOrder(int $idSalesOrder): array;

    /**
     * @param int $idSalesOrderItem
     *
     * @return \Generated\Shared\Transfer\ItemRefundTransfer[]
     */
    public function findOrderItemRefundsByIdSalesOrderItem(int $idSalesOrderItem): array;

    /**
     * @param int $idSalesExpense
     *
     * @return \Generated\Shared\Transfer\ExpenseRefundTransfer[]
     */
    public function findOrderExpenseRefundsByIdSalesExpense(int $idSalesExpense): array;
}
