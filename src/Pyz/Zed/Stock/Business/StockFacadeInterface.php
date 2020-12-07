<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Stock\Business;

use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\Stock\Business\StockFacadeInterface as SprykerStockFacadeInterface;

interface StockFacadeInterface extends SprykerStockFacadeInterface
{
    /**
     * Specification:
     * - Expands order with stock transfers.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function expandOrderWithStock(OrderTransfer $orderTransfer): OrderTransfer;
}
