<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\Weclapp\Client\Warehouse\GetWarehouse;

use Generated\Shared\Transfer\WeclappWarehouseTransfer;

interface GetWarehouseClientInterface
{
    /**
     * Get warehouse
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\WeclappWarehouseTransfer $weclappWarehouseTransfer
     *
     * @return \Generated\Shared\Transfer\WeclappWarehouseTransfer|null
     */
    public function getWarehouse(
        WeclappWarehouseTransfer $weclappWarehouseTransfer
    ): ?WeclappWarehouseTransfer;
}
