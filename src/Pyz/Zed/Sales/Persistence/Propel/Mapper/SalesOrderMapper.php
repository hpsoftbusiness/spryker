<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Sales\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\TaxTotalTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Spryker\Zed\Sales\Persistence\Propel\Mapper\SalesOrderMapper as SprykerSalesOrderMapper;

class SalesOrderMapper extends SprykerSalesOrderMapper
{
    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $spySalesOrderEntity
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function mapSalesOrderEntityToOrderTransfer(SpySalesOrder $spySalesOrderEntity, OrderTransfer $orderTransfer): OrderTransfer
    {
        $orderTransfer->fromArray($spySalesOrderEntity->toArray(), true);

        $salesOrderTotalsEntity = $spySalesOrderEntity->getLastOrderTotals();
        if ($salesOrderTotalsEntity !== null) {
            $salesOrderTotalsTransfer = $orderTransfer->getTotals() ?: new TotalsTransfer();
            $taxTotalTransfer = $salesOrderTotalsTransfer->getTaxTotal() ?: new TaxTotalTransfer();
            $taxTotalTransfer->setAmount($salesOrderTotalsEntity->getTaxTotal());
            $salesOrderTotalsTransfer->fromArray($salesOrderTotalsEntity->toArray(), true);
            $salesOrderTotalsTransfer->setTaxTotal($taxTotalTransfer);
            $salesOrderTotalsTransfer->setExpenseTotal($salesOrderTotalsEntity->getOrderExpenseTotal());
            $orderTransfer->setTotals($salesOrderTotalsTransfer);
        }

        return $orderTransfer;
    }
}
