<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Shipment\Business\Model;

use Generated\Shared\Transfer\AddressTransfer;
use Spryker\Zed\Shipment\Business\Model\ShipmentTaxRateCalculator as SprykerShipmentTaxRateCalculator;

class ShipmentTaxRateCalculator extends SprykerShipmentTaxRateCalculator
{
    /**
     * @param \Generated\Shared\Transfer\AddressTransfer|null $shippingAddressTransfer
     *
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
