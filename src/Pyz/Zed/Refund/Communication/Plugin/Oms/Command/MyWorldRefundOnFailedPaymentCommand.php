<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Refund\Communication\Plugin\Oms\Command;

use ArrayObject;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\RefundTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Pyz\Shared\MyWorldPayment\MyWorldPaymentConfig;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject;
use Spryker\Zed\Oms\Dependency\Plugin\Command\CommandByOrderInterface;

/**
 * @method \Pyz\Zed\Refund\Business\RefundFacadeInterface getFacade()
 * @method \Pyz\Zed\Refund\RefundConfig getConfig()
 * @method \Pyz\Zed\Refund\Communication\RefundCommunicationFactory getFactory()
 */
class MyWorldRefundOnFailedPaymentCommand extends AbstractPlugin implements CommandByOrderInterface
{
    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem[] $orderItems
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     * @param \Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject $data
     *
     * @return array
     */
    public function run(array $orderItems, SpySalesOrder $orderEntity, ReadOnlyArrayObject $data): array
    {
        $orderTransfer = $this->getFactory()->getSalesFacade()->getOrderByIdSalesOrder($orderEntity->getIdSalesOrder());
        $this->unsetIrrelevantPayments($orderTransfer->getPayments());
        $refundTransfer = $this->calculateRefunds($orderItems, $orderTransfer);
        $this->storeItemsPaymentRefunds($refundTransfer->getItems());
        $this->storeExpensesPaymentRefunds($refundTransfer->getExpenses());

        $this->getFacade()->processRefund($orderItems, $orderEntity);

        return [];
    }

    /**
     * @param array $orderItems
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\RefundTransfer
     */
    private function calculateRefunds(array $orderItems, OrderTransfer $orderTransfer): RefundTransfer
    {
        $refundTransfer = new RefundTransfer();
        foreach ($this->getFactory()->getRefundCalculatorPlugins() as $calculatorPlugin) {
            $refundTransfer = $calculatorPlugin->calculateRefund($refundTransfer, $orderTransfer, $orderItems);
        }

        return $refundTransfer;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\PaymentTransfer[] $paymentTransfers
     *
     * @return void
     */
    private function unsetIrrelevantPayments(ArrayObject $paymentTransfers): void
    {
        foreach ($paymentTransfers as $index => $paymentTransfer) {
            if ($paymentTransfer->getPaymentProvider() !== MyWorldPaymentConfig::PAYMENT_PROVIDER_NAME_MY_WORLD) {
                $paymentTransfers->offsetUnset($index);
            }
        }
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ExpenseTransfer[] $expenseTransfers
     *
     * @return void
     */
    private function storeExpensesPaymentRefunds(ArrayObject $expenseTransfers): void
    {
        foreach ($expenseTransfers as $expenseTransfer) {
            $this->saveExpenseRefunds($expenseTransfer->getRefunds());
        }
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return void
     */
    private function storeItemsPaymentRefunds(ArrayObject $itemTransfers): void
    {
        foreach ($itemTransfers as $itemTransfer) {
            $this->saveItemRefunds($itemTransfer->getRefunds());
        }
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ExpenseRefundTransfer[] $expenseRefundTransfers
     *
     * @return void
     */
    private function saveExpenseRefunds(ArrayObject $expenseRefundTransfers): void
    {
        foreach ($expenseRefundTransfers as $expenseRefundTransfer) {
            $this->getFacade()->saveOrderExpenseRefund($expenseRefundTransfer);
        }
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemRefundTransfer[] $itemRefundTransfers
     *
     * @return void
     */
    private function saveItemRefunds(ArrayObject $itemRefundTransfers): void
    {
        foreach ($itemRefundTransfers as $itemRefundTransfer) {
            $this->getFacade()->saveOrderItemRefund($itemRefundTransfer);
        }
    }
}
