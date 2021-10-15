<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\Weclapp\Client\Warehouse\GetWarehouse;

use Generated\Shared\Transfer\WeclappWarehouseTransfer;
use Pyz\Client\Weclapp\Client\Warehouse\AbstractWeclappWarehouseClient;

class GetWarehouseClient extends AbstractWeclappWarehouseClient implements GetWarehouseClientInterface
{
    protected const REQUEST_METHOD = 'GET';
    protected const ACTION_URL = '/warehouse/id/%s';

    /**
     * @param \Generated\Shared\Transfer\WeclappWarehouseTransfer $weclappWarehouseTransfer
     *
     * @return \Generated\Shared\Transfer\WeclappWarehouseTransfer|null
     */
    public function getWarehouse(
        WeclappWarehouseTransfer $weclappWarehouseTransfer
    ): ?WeclappWarehouseTransfer {
        $weclappResponse = $this->callWeclapp(
            static::REQUEST_METHOD,
            $this->getActionUrl($weclappWarehouseTransfer)
        );
        $weclappWarehouseData = json_decode($weclappResponse->getBody()->__toString(), true);
        if (!$weclappWarehouseData) {
            return null;
        }

        return $this->warehouseMapper->mapWeclappDataToWeclappWarehouse($weclappWarehouseData);
    }

    /**
     * @param \Generated\Shared\Transfer\WeclappWarehouseTransfer $weclappWarehouseTransfer
     *
     * @return string
     */
    protected function getActionUrl(WeclappWarehouseTransfer $weclappWarehouseTransfer): string
    {
        return sprintf(static::ACTION_URL, $weclappWarehouseTransfer->getId());
    }
}
