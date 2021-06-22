<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Refund\Business\Processor\Aggregator;

interface PaymentRefundsAggregatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\ItemRefundTransfer[] $itemRefunds
     * @param \Generated\Shared\Transfer\ExpenseRefundTransfer[] $expenseRefunds
     * @param \Generated\Shared\Transfer\PaymentTransfer[] $payments
     *
     * @return \Generated\Shared\Transfer\RefundTransfer[]
     */
    public function aggregate(array $itemRefunds, array $expenseRefunds, array $payments): array;
}
