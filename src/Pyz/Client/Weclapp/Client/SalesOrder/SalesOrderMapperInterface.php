<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\Weclapp\Client\SalesOrder;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\WeclappSalesOrderTransfer;

interface SalesOrderMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\WeclappSalesOrderTransfer
     */
    public function mapOrderToWeclappSalesOrder(OrderTransfer $orderTransfer): WeclappSalesOrderTransfer;
}
