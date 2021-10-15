<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\Weclapp\Client\Warehouse;

use Generated\Shared\Transfer\WeclappWarehouseTransfer;

interface WarehouseMapperInterface
{
    /**
     * @param array $weclappWarehouseData
     *
     * @return \Generated\Shared\Transfer\WeclappWarehouseTransfer
     */
    public function mapWeclappDataToWeclappWarehouse(array $weclappWarehouseData): WeclappWarehouseTransfer;
}
