<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Refund\Business\Calculator\Expense;

use Generated\Shared\Transfer\ExpenseTransfer;

class ExpensePaymentRefundCalculator implements ExpensePaymentRefundCalculatorInterface
{
    /**
     * @var \Pyz\Zed\Refund\Dependency\Plugin\ExpenseRefundCalculatorPluginInterface[]
     */
    private $calculatorPlugins;

    /**
     * @param \Pyz\Zed\Refund\Dependency\Plugin\ExpenseRefundCalculatorPluginInterface[] $calculatorPlugins
     */
    public function __construct(array $calculatorPlugins)
    {
        $this->calculatorPlugins = $calculatorPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\ExpenseTransfer $expenseTransfer
     * @param \Generated\Shared\Transfer\PaymentTransfer[] $paymentTransfers
     *
     * @return \Generated\Shared\Transfer\ExpenseTransfer
     */
    public function calculateExpenseRefunds(ExpenseTransfer $expenseTransfer, array $paymentTransfers): ExpenseTransfer
    {
        $expenseCanceledAmount = $expenseTransfer->getCanceledAmount();
        foreach ($this->calculatorPlugins as $calculatorPlugin) {
            if ($expenseTransfer->getCanceledAmount() === 0) {
                break;
            }

            $paymentTransfer = $calculatorPlugin->findApplicablePayment($paymentTransfers);
            if (!$paymentTransfer || $paymentTransfer->getRefundableAmount() === 0) {
                continue;
            }

            $expenseRefundTransfer = $calculatorPlugin->calculateExpenseRefund($expenseTransfer, $paymentTransfer);
            if (!$expenseRefundTransfer) {
                continue;
            }

            $expenseTransfer->addRefund($expenseRefundTransfer);
            $expenseTransfer->setCanceledAmount($expenseTransfer->getCanceledAmount() - $expenseRefundTransfer->getAmount());
        }

        $expenseTransfer->setCanceledAmount($expenseCanceledAmount);

        return $expenseTransfer;
    }
}
