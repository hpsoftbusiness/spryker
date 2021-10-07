<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Shipment\Business;

use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Spryker\Zed\Shipment\Business\ShipmentFacade as SprykerShipmentFacade;

/**
 * @method \Pyz\Zed\Shipment\Business\ShipmentBusinessFactory getFactory()
 */
class ShipmentFacade extends SprykerShipmentFacade implements ShipmentFacadeInterface
{
    /**
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer
     */
    public function createDefaultShipmentMethodTransfer(): ShipmentMethodTransfer
    {
        return $this->getFactory()
            ->createShipmentMethodReader()
            ->fetchDefaultOrFirstAvailableShipmentMethod();
    }
}
