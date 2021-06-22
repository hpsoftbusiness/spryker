<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Refund\Persistence;

use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Pyz\Zed\Refund\Persistence\RefundPersistenceFactory getFactory()
 */
class RefundRepository extends AbstractRepository implements RefundRepositoryInterface
{
    /**
     * @param int $idSalesOrder
     *
     * @return \Generated\Shared\Transfer\ItemRefundTransfer[]
     */
    public function findOrderItemRefundsByIdSalesOrder(int $idSalesOrder): array
    {
        $itemRefundEntities = $this->getFactory()->createPyzSalesOrderItemRefundQuery()
            ->useSpySalesOrderItemQuery()
            ->filterByFkSalesOrder($idSalesOrder)
            ->endUse()
            ->find();

        if ($itemRefundEntities->count() === 0) {
            return [];
        }

        return $this->getFactory()->createItemRefundMapper()
            ->mapItemRefundEntityCollectionToTransfers($itemRefundEntities);
    }

    /**
     * @param int $idSalesOrder
     *
     * @return \Generated\Shared\Transfer\ExpenseRefundTransfer[]
     */
    public function findOrderExpenseRefundsByIdSalesOrder(int $idSalesOrder): array
    {
        $expenseRefundEntityCollection = $this->getFactory()->createPyzSalesExpenseRefundQuery()
            ->useSpySalesExpenseQuery()
            ->filterByFkSalesOrder($idSalesOrder)
            ->endUse()
            ->find();

        if ($expenseRefundEntityCollection->count() === 0) {
            return [];
        }

        return $this->getFactory()->createExpenseRefundMapper()
            ->mapExpenseRefundEntityCollectionToTransfers($expenseRefundEntityCollection->getData());
    }

    /**
     * @param int $idSalesOrderItem
     *
     * @return \Generated\Shared\Transfer\ItemRefundTransfer[]
     */
    public function findOrderItemRefundsByIdSalesOrderItem(int $idSalesOrderItem): array
    {
        $itemRefundEntities = $this->getFactory()->createPyzSalesOrderItemRefundQuery()
            ->filterByFkSalesOrderItem($idSalesOrderItem)
            ->find();

        if ($itemRefundEntities->count() === 0) {
            return [];
        }

        return $this->getFactory()->createItemRefundMapper()
            ->mapItemRefundEntityCollectionToTransfers($itemRefundEntities);
    }

    /**
     * @param int $idSalesExpense
     *
     * @return \Generated\Shared\Transfer\ExpenseRefundTransfer[]
     */
    public function findOrderExpenseRefundsByIdSalesExpense(int $idSalesExpense): array
    {
        $expenseRefundEntityCollection = $this->getFactory()->createPyzSalesExpenseRefundQuery()
            ->filterByFkSalesExpense($idSalesExpense)
            ->find();

        if ($expenseRefundEntityCollection->count() === 0) {
            return [];
        }

        return $this->getFactory()->createExpenseRefundMapper()
            ->mapExpenseRefundEntityCollectionToTransfers($expenseRefundEntityCollection->getData());
    }
}
