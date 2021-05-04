<?php

namespace Pyz\Zed\Shipment\Business;

use Pyz\Zed\Shipment\Business\Model\ShipmentTaxRateCalculator;
use Spryker\Zed\Shipment\Business\ShipmentBusinessFactory as SprykerShipmentBusinessFactory;

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
}
