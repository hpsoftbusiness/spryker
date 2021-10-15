<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Sales\Business;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\SalesOrderFilterTransfer;
use Spryker\Zed\Sales\Business\SalesFacadeInterface as SprykerSalesFacadeInterface;

interface SalesFacadeInterface extends SprykerSalesFacadeInterface
{
    /**
     * Specification:
     * - Gets order IDs by SalesOrderFilterTransfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SalesOrderFilterTransfer $salesOrderFilterTransfer
     *
     * @return int[]
     */
    public function getOrderIdsBySalesOrderFilter(SalesOrderFilterTransfer $salesOrderFilterTransfer): array;

    /**
     * Specification:
     * - Finds order using the given ID sales order.
     * - Gets order transfer hydrated by data that needed exactly for exporting.
     *
     * @api
     *
     * @param int $idSalesOrder
     *
     * @throws \Spryker\Zed\Sales\Business\Exception\InvalidSalesOrderException
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function getOrderForExportByIdSalesOrder(int $idSalesOrder): OrderTransfer;

    /**
     * @param string $orderReference
     *
     * @return array
     */
    public function getOrderItemsIdsByOrderReference(string $orderReference): array;
}
