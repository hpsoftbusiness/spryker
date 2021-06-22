<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Sales\Business;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\SalesOrderFilterTransfer;
use Spryker\Zed\Refund\Dependency\Facade\RefundToSalesInterface;
use Spryker\Zed\Sales\Business\SalesFacade as SprykerSalesFacade;

/**
 * @method \Pyz\Zed\Sales\Business\SalesBusinessFactory getFactory()
 * @method \Pyz\Zed\Sales\Persistence\SalesRepositoryInterface getRepository()
 */
class SalesFacade extends SprykerSalesFacade implements SalesFacadeInterface, RefundToSalesInterface
{
    /**
     * @inheritDoc
     */
    public function getOrderIdsBySalesOrderFilter(SalesOrderFilterTransfer $salesOrderFilterTransfer): array
    {
        return $this->getRepository()
            ->getOrderIdsBySalesOrderFilter($salesOrderFilterTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idSalesOrder
     *
     * @throws \Spryker\Zed\Sales\Business\Exception\InvalidSalesOrderException
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function getOrderForExportByIdSalesOrder(int $idSalesOrder): OrderTransfer
    {
        return $this->getFactory()
            ->createOrderForExportHydrator()
            ->hydrateOrderTransferFromPersistenceByIdSalesOrder($idSalesOrder);
    }
}
