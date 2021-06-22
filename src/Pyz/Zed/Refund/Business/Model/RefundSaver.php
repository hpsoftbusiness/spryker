<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Refund\Business\Model;

use Generated\Shared\Transfer\RefundTransfer;
use Pyz\Zed\Refund\Persistence\RefundEntityManagerInterface;
use Spryker\Zed\Refund\Business\Model\RefundSaver as SprykerRefundSaver;
use Spryker\Zed\Refund\Dependency\Facade\RefundToCalculationInterface;
use Spryker\Zed\Refund\Dependency\Facade\RefundToSalesInterface;
use Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface;

class RefundSaver extends SprykerRefundSaver
{
    /**
     * @var \Pyz\Zed\Refund\Persistence\RefundEntityManagerInterface
     */
    private $entityManager;

    /**
     * @param \Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface $salesQueryContainer
     * @param \Spryker\Zed\Refund\Dependency\Facade\RefundToSalesInterface $saleFacade
     * @param \Spryker\Zed\Refund\Dependency\Facade\RefundToCalculationInterface $calculationFacade
     * @param \Pyz\Zed\Refund\Persistence\RefundEntityManagerInterface $entityManager
     */
    public function __construct(
        SalesQueryContainerInterface $salesQueryContainer,
        RefundToSalesInterface $saleFacade,
        RefundToCalculationInterface $calculationFacade,
        RefundEntityManagerInterface $entityManager
    ) {
        parent::__construct($salesQueryContainer, $saleFacade, $calculationFacade);

        $this->entityManager = $entityManager;
    }

    /**
     * @param \Generated\Shared\Transfer\RefundTransfer $refundTransfer
     *
     * @return void
     */
    protected function storeRefund(RefundTransfer $refundTransfer): void
    {
        parent::storeRefund($refundTransfer);

        $this->storeItemsPaymentRefunds($refundTransfer->getItems());
        $this->storeExpensesPaymentRefunds($refundTransfer->getExpenses());
    }

    /**
     * @param iterable|\Generated\Shared\Transfer\ExpenseTransfer[] $expenseTransfers
     *
     * @return void
     */
    private function storeExpensesPaymentRefunds(iterable $expenseTransfers): void
    {
        foreach ($expenseTransfers as $expenseTransfer) {
            $this->saveExpenseRefunds($expenseTransfer->getRefunds());
        }
    }

    /**
     * @param iterable|\Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return void
     */
    private function storeItemsPaymentRefunds(iterable $itemTransfers): void
    {
        foreach ($itemTransfers as $itemTransfer) {
            $this->saveItemRefunds($itemTransfer->getRefunds());
        }
    }

    /**
     * @param iterable|\Generated\Shared\Transfer\ExpenseRefundTransfer[] $expenseRefundTransfers
     *
     * @return void
     */
    private function saveExpenseRefunds(iterable $expenseRefundTransfers): void
    {
        foreach ($expenseRefundTransfers as $expenseRefundTransfer) {
            $this->entityManager->saveSalesExpenseRefund($expenseRefundTransfer);
        }
    }

    /**
     * @param iterable|\Generated\Shared\Transfer\ItemRefundTransfer[] $itemRefundTransfers
     *
     * @return void
     */
    private function saveItemRefunds(iterable $itemRefundTransfers): void
    {
        foreach ($itemRefundTransfers as $itemRefundTransfer) {
            $this->entityManager->saveSalesOrderItemRefund($itemRefundTransfer);
        }
    }
}
