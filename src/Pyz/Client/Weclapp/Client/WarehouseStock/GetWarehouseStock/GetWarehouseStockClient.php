<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\Weclapp\Client\WarehouseStock\GetWarehouseStock;

use Generated\Shared\Transfer\WeclappWarehouseStockTransfer;
use Pyz\Client\Weclapp\Client\WarehouseStock\AbstractWeclappWarehouseStockClient;

class GetWarehouseStockClient extends AbstractWeclappWarehouseStockClient implements GetWarehouseStockClientInterface
{
    protected const REQUEST_METHOD = 'GET';
    protected const ACTION_URL = '/warehouseStock/id/%s';

    /**
     * @param \Generated\Shared\Transfer\WeclappWarehouseStockTransfer $weclappWarehouseStockTransfer
     *
     * @return \Generated\Shared\Transfer\WeclappWarehouseStockTransfer|null
     */
    public function getWarehouseStock(
        WeclappWarehouseStockTransfer $weclappWarehouseStockTransfer
    ): ?WeclappWarehouseStockTransfer {
        $weclappResponse = $this->callWeclapp(
            static::REQUEST_METHOD,
            $this->getActionUrl($weclappWarehouseStockTransfer)
        );
        $weclappWarehouseStockData = json_decode($weclappResponse->getBody()->__toString(), true);
        if (!$weclappWarehouseStockData) {
            return null;
        }

        return $this->warehouseStockMapper->mapWeclappDataToWeclappWarehouseStock($weclappWarehouseStockData);
    }

    /**
     * @param \Generated\Shared\Transfer\WeclappWarehouseStockTransfer $weclappWarehouseStockTransfer
     *
     * @return string
     */
    protected function getActionUrl(WeclappWarehouseStockTransfer $weclappWarehouseStockTransfer): string
    {
        return sprintf(static::ACTION_URL, $weclappWarehouseStockTransfer->getId());
    }
}
