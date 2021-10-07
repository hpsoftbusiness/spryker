<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\StepEngineExtension\Dependency\Plugin;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use SprykerShop\Yves\CheckoutPageExtension\Dependency\Plugin\StepEngine\CheckoutPageStepEnginePreRenderPluginInterface;

class DefaultShippingMethodPlugin implements CheckoutPageStepEnginePreRenderPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $dataTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function execute(AbstractTransfer $dataTransfer): AbstractTransfer
    {
        if (!$dataTransfer instanceof QuoteTransfer) {
            return $dataTransfer;
        }

        $shipment = $dataTransfer->getShipment();

        if ($shipment === null) {
            return $dataTransfer;
        }

        $shipmentMethod = $shipment->getMethod() ?? new ShipmentMethodTransfer();
        $shipment->setShipmentSelection((string)$shipmentMethod->getIdShipmentMethod());

        foreach ($dataTransfer->getItems() as $item) {
            $itemShipmentTransfer = $item->getShipment() ?? $shipment;

            /**
             * you should not set shipping without address
             * @see \SprykerShop\Yves\CustomerPage\Form\DataProvider\CheckoutAddressFormDataProvider::setBillingSameAsShipping
             */
            if ($itemShipmentTransfer->getShippingAddress() === null) {
                continue;
            }

            if ($itemShipmentTransfer->getMethod() === null) {
                $itemShipmentTransfer
                    ->setMethod($shipmentMethod)
                    ->setShipmentSelection((string)$shipmentMethod->getIdShipmentMethod());
            }

            $item->setShipment($itemShipmentTransfer);
        }

        return $dataTransfer;
    }
}
