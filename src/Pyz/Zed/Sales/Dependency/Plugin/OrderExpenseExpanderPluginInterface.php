<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Sales\Dependency\Plugin;

interface OrderExpenseExpanderPluginInterface
{
    /**
     * Specification:
     * - Expands order expenses that were read from Persistence.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ExpenseTransfer[] $expenseTransfers
     *
     * @return \Generated\Shared\Transfer\ExpenseTransfer[]
     */
    public function expand(array $expenseTransfers): array;
}
