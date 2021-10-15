<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\Weclapp\Client\Shipment\GetShipment;

use Generated\Shared\Transfer\WeclappShipmentTransfer;

interface GetShipmentClientInterface
{
    /**
     * Get shipment
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\WeclappShipmentTransfer $weclappShipmentTransfer
     *
     * @return \Generated\Shared\Transfer\WeclappShipmentTransfer|null
     */
    public function getShipment(
        WeclappShipmentTransfer $weclappShipmentTransfer
    ): ?WeclappShipmentTransfer;
}
