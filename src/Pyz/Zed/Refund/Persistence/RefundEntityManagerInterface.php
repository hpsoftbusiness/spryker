<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Refund\Persistence;

use Generated\Shared\Transfer\ExpenseRefundTransfer;
use Generated\Shared\Transfer\ItemRefundTransfer;

interface RefundEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\ExpenseRefundTransfer $expenseRefundTransfer
     *
     * @return void
     */
    public function saveSalesExpenseRefund(ExpenseRefundTransfer $expenseRefundTransfer): void;

    /**
     * @param \Generated\Shared\Transfer\ItemRefundTransfer $itemRefundTransfer
     *
     * @return void
     */
    public function saveSalesOrderItemRefund(ItemRefundTransfer $itemRefundTransfer): void;
}
