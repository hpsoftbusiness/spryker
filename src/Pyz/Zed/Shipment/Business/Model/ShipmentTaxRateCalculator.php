<?php

namespace Pyz\Zed\Shipment\Business\Model;

use Generated\Shared\Transfer\AddressTransfer;
use Spryker\Zed\Shipment\Business\Model\ShipmentTaxRateCalculator as SprykerShipmentTaxRateCalculator;

class ShipmentTaxRateCalculator extends SprykerShipmentTaxRateCalculator
{
    /**
     * @param \Generated\Shared\Transfer\AddressTransfer|null $shippingAddressTransfer
     * @return string
     */
    protected function getCountryIso2Code(?AddressTransfer $shippingAddressTransfer): string
    {
        if ($shippingAddressTransfer && $shippingAddressTransfer->getIso2Code() !== null) {
            return $shippingAddressTransfer->getIso2Code();
        }

        return $this->taxFacade->getDefaultTaxCountryIso2Code();
    }
}
