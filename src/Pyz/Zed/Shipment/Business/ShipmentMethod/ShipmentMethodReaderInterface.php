<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Shipment\Business\ShipmentMethod;

use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Spryker\Zed\Shipment\Business\ShipmentMethod\ShipmentMethodReaderInterface as SprykerShipmentMethodReaderInterface;

interface ShipmentMethodReaderInterface extends SprykerShipmentMethodReaderInterface
{
    /**
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer|null
     */
    public function getDefaultShipmentMethod(): ?ShipmentMethodTransfer;
}
