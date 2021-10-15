<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\Weclapp\Client\WarehouseStock\GetWarehouseStock;

use Generated\Shared\Transfer\WeclappWarehouseStockTransfer;

interface GetWarehouseStockClientInterface
{
    /**
     * Get warehouse stock
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\WeclappWarehouseStockTransfer $weclappWarehouseStockTransfer
     *
     * @return \Generated\Shared\Transfer\WeclappWarehouseStockTransfer|null
     */
    public function getWarehouseStock(
        WeclappWarehouseStockTransfer $weclappWarehouseStockTransfer
    ): ?WeclappWarehouseStockTransfer;
}
