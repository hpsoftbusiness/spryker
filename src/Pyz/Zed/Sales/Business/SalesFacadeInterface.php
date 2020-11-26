<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Sales\Business;

use Generated\Shared\Transfer\SalesOrderFilterTransfer;
use Spryker\Zed\Sales\Business\SalesFacadeInterface as SprykerSalesFacadeInterface;

interface SalesFacadeInterface extends SprykerSalesFacadeInterface
{
    /**
     * Specification:
     * - Gets orders by SalesOrderFilterTransfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SalesOrderFilterTransfer $salesOrderFilterTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer[]
     */
    public function getOrdersBySalesOrderFilter(SalesOrderFilterTransfer $salesOrderFilterTransfer): array;
}
