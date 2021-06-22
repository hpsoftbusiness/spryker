<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Refund\Communication\Plugin\Sales;

use ArrayObject;
use Generated\Shared\Transfer\ExpenseTransfer;
use Pyz\Zed\Sales\Dependency\Plugin\OrderExpenseExpanderPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Pyz\Zed\Refund\Business\RefundFacadeInterface getFacade()
 * @method \Pyz\Zed\Refund\RefundConfig getConfig()
 * @method \Pyz\Zed\Refund\Persistence\RefundRepositoryInterface getRepository()()
 * @method \Pyz\Zed\Refund\Communication\RefundCommunicationFactory getFactory()
 */
class ExpenseRefundExpanderPlugin extends AbstractPlugin implements OrderExpenseExpanderPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\ExpenseTransfer[] $expenseTransfers
     *
     * @return \Generated\Shared\Transfer\ExpenseTransfer[]
     */
    public function expand(array $expenseTransfers): array
    {
        foreach ($expenseTransfers as $expenseTransfer) {
            $this->expandExpenseWithRefunds($expenseTransfer);
        }

        return $expenseTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ExpenseTransfer $expenseTransfer
     *
     * @return void
     */
    private function expandExpenseWithRefunds(ExpenseTransfer $expenseTransfer): void
    {
        $expenseRefundTransfers = $this->getRepository()->findOrderExpenseRefundsByIdSalesExpense(
            $expenseTransfer->getIdSalesExpense()
        );

        $expenseTransfer->setRefunds(new ArrayObject($expenseRefundTransfers));
    }
}
