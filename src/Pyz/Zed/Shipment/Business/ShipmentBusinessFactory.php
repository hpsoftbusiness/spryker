<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Shipment\Business;

use Pyz\Zed\Shipment\Business\Model\ShipmentTaxRateCalculator;
use Pyz\Zed\Shipment\Business\ShipmentMethod\ShipmentMethodReader;
use Spryker\Zed\Shipment\Business\ShipmentBusinessFactory as SprykerShipmentBusinessFactory;
use Spryker\Zed\Shipment\Business\ShipmentMethod\ShipmentMethodReaderInterface;

class ShipmentBusinessFactory extends SprykerShipmentBusinessFactory
{
    /**
     * @return \Pyz\Zed\Shipment\Business\Model\ShipmentTaxRateCalculator
     */
    public function createShipmentTaxCalculator()
    {
        return new ShipmentTaxRateCalculator(
            $this->getQueryContainer(),
            $this->getTaxFacade(),
            $this->getShipmentService()
        );
    }

    /**
     * @return \Pyz\Zed\Shipment\Business\ShipmentMethod\ShipmentMethodReaderInterface
     */
    public function createShipmentMethodReader(): ShipmentMethodReaderInterface
    {
        return new ShipmentMethodReader(
            $this->getRepository(),
            $this->getCurrencyFacade()
        );
    }
}
