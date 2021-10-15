<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\Weclapp\Client\Shipment;

use Generated\Shared\Transfer\WeclappShipmentTransfer;

class ShipmentMapper implements ShipmentMapperInterface
{
    /**
     * @param array $weclappShipmentData
     *
     * @return \Generated\Shared\Transfer\WeclappShipmentTransfer
     */
    public function mapWeclappDataToWeclappShipment(array $weclappShipmentData): WeclappShipmentTransfer
    {
        return (new WeclappShipmentTransfer())->fromArray($weclappShipmentData, true);
    }
}
