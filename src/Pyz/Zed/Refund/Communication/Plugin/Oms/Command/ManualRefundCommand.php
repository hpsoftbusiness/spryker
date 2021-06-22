<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Refund\Communication\Plugin\Oms\Command;

use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Pyz\Zed\Refund\RefundConfig;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject;
use Spryker\Zed\Oms\Dependency\Plugin\Command\CommandByOrderInterface;

/**
 * @method \Pyz\Zed\Refund\Business\RefundFacadeInterface getFacade()
 * @method \Pyz\Zed\Refund\RefundConfig getConfig()
 * @method \Pyz\Zed\Refund\Communication\RefundCommunicationFactory getFactory()
 * @method \Pyz\Zed\Refund\Persistence\RefundRepositoryInterface getRepository()
 */
class ManualRefundCommand extends AbstractPlugin implements CommandByOrderInterface
{
    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem[] $orderItems
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     * @param \Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject $data
     *
     * @return array
     */
    public function run(array $orderItems, SpySalesOrder $orderEntity, ReadOnlyArrayObject $data)
    {
        $orderItemIds = $this->collectOrderItemIds($orderItems);
        $itemRefundTransfers = $this->getItemRefunds($orderItemIds);
        $this->saveItemRefunds($itemRefundTransfers);
        $expenseRefundTransfers = $this->getRepository()->findOrderExpenseRefundsByIdSalesOrder(
            $orderEntity->getIdSalesOrder()
        );
        $this->saveExpenseRefunds($expenseRefundTransfers);

        return [];
    }

    /**
     * @param \Generated\Shared\Transfer\ExpenseRefundTransfer[] $expenseRefundTransfers
     *
     * @return void
     */
    private function saveExpenseRefunds(array $expenseRefundTransfers): void
    {
        foreach ($expenseRefundTransfers as $expenseRefundTransfer) {
            $expenseRefundTransfer->setStatus(RefundConfig::PAYMENT_REFUND_STATUS_PROCESSED);
            $this->getFacade()->saveOrderExpenseRefund($expenseRefundTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ItemRefundTransfer[] $itemRefundTransfers
     *
     * @return void
     */
    private function saveItemRefunds(array $itemRefundTransfers): void
    {
        foreach ($itemRefundTransfers as $itemRefundTransfer) {
            $itemRefundTransfer->setStatus(RefundConfig::PAYMENT_REFUND_STATUS_PROCESSED);
            $this->getFacade()->saveOrderItemRefund($itemRefundTransfer);
        }
    }

    /**
     * @param int[] $orderItemIds
     *
     * @return \Generated\Shared\Transfer\ItemRefundTransfer[]
     */
    private function getItemRefunds(array $orderItemIds): array
    {
        $itemRefundTransfers = [];

        foreach ($orderItemIds as $idOrderItem) {
            $itemRefundTransfers = array_merge(
                $itemRefundTransfers,
                $this->getRepository()->findOrderItemRefundsByIdSalesOrderItem($idOrderItem)
            );
        }

        return $itemRefundTransfers;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem[] $orderItems
     *
     * @return int[]
     */
    private function collectOrderItemIds(array $orderItems): array
    {
        return array_map(
            function (SpySalesOrderItem $spySalesOrderItem): int {
                return $spySalesOrderItem->getIdSalesOrderItem();
            },
            $orderItems
        );
    }
}
