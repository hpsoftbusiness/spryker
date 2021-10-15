<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\Weclapp\Client\WarehouseStock;

use Generated\Shared\Transfer\WeclappWarehouseStockTransfer;

interface WarehouseStockMapperInterface
{
    /**
     * @param array $weclappWarehouseStockData
     *
     * @return \Generated\Shared\Transfer\WeclappWarehouseStockTransfer
     */
    public function mapWeclappDataToWeclappWarehouseStock(array $weclappWarehouseStockData): WeclappWarehouseStockTransfer;
}
