<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Shipment\Business\ShipmentMethod;

use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Pyz\Shared\Shipment\ShipmentConfig;
use Spryker\Zed\Shipment\Business\ShipmentMethod\ShipmentMethodReader as SprykerShipmentMethodReader;

/**
 * Class ShipmentMethodReader
 *
 * @package Pyz\Zed\Shipment\Business\ShipmentMethod
 */
class ShipmentMethodReader extends SprykerShipmentMethodReader implements ShipmentMethodReaderInterface
{
    /**
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer|null
     */
    public function getDefaultShipmentMethod(): ?ShipmentMethodTransfer
    {
        return $this->findShipmentMethodById(ShipmentConfig::DEFAULT_SHIPMENT_METHOD_ID);
    }
}
