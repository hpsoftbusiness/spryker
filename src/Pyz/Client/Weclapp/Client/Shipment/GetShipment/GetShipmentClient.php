<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\Weclapp\Client\Shipment\GetShipment;

use Generated\Shared\Transfer\WeclappShipmentTransfer;
use Pyz\Client\Weclapp\Client\Shipment\AbstractWeclappShipmentClient;

class GetShipmentClient extends AbstractWeclappShipmentClient implements GetShipmentClientInterface
{
    protected const REQUEST_METHOD = 'GET';
    protected const ACTION_URL = '/shipment/id/%s';

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
    ): ?WeclappShipmentTransfer {
        $weclappResponse = $this->callWeclapp(
            static::REQUEST_METHOD,
            $this->getActionUrl($weclappShipmentTransfer)
        );
        $weclappShipmentData = json_decode($weclappResponse->getBody()->__toString(), true);
        if (!$weclappShipmentData) {
            return null;
        }

        return $this->shipmentMapper->mapWeclappDataToWeclappShipment($weclappShipmentData);
    }

    /**
     * @param \Generated\Shared\Transfer\WeclappShipmentTransfer $weclappShipmentTransfer
     *
     * @return string
     */
    protected function getActionUrl(WeclappShipmentTransfer $weclappShipmentTransfer): string
    {
        return sprintf(static::ACTION_URL, $weclappShipmentTransfer->getId());
    }
}
