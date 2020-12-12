<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Sales\Persistence;

use Generated\Shared\Transfer\SalesOrderFilterTransfer;
use Spryker\Zed\Sales\Persistence\SalesRepositoryInterface as SprykerSalesRepositoryInterface;

interface SalesRepositoryInterface extends SprykerSalesRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\SalesOrderFilterTransfer $salesOrderFilterTransfer
     *
     * @return int[]
     */
    public function getOrderIdsBySalesOrderFilter(SalesOrderFilterTransfer $salesOrderFilterTransfer): array;
}
