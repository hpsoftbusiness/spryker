<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Cart\Plugin;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Pyz\Zed\Shipment\Business\ShipmentFacadeInterface;
use Spryker\Shared\ShipmentsRestApi\ShipmentsRestApiConfig;
use Spryker\Zed\CartExtension\Dependency\Plugin\ItemExpanderPluginInterface;

class ShipmentDefaultCartExpander implements ItemExpanderPluginInterface
{
    /**
     * @var \Pyz\Zed\Shipment\Business\ShipmentFacadeInterface
     */
    private $shipmentFacade;

    /**
     * @param \Pyz\Zed\Shipment\Business\ShipmentFacadeInterface $shipmentFacade
     */
    public function __construct(ShipmentFacadeInterface $shipmentFacade)
    {
        $this->shipmentFacade = $shipmentFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function expandItems(CartChangeTransfer $cartChangeTransfer)
    {
        $quoteTransfer = $cartChangeTransfer->getQuote();

        if ($quoteTransfer->getShipment() !== null) {
            return $cartChangeTransfer;
        }

        $shipmentTransfer = new ShipmentTransfer();

        $shippingMethodTransfer = $this->shipmentFacade->createDefaultShipmentMethodTransfer();

        $shipmentTransfer
            ->setMethod($shippingMethodTransfer)
            ->setShipmentSelection((string)$shippingMethodTransfer->getIdShipmentMethod());

        $quoteTransfer->setShipment($shipmentTransfer);
        $quoteTransfer->addExpense(
            $this->createShippingExpenseTransfer($shipmentTransfer)
        );

        foreach ($quoteTransfer->getItems() as $item) {
            $itemShipment = $item->getShipment() ?? $shipmentTransfer;
            $itemShipment->setShipmentSelection((string)$itemShipment->getMethod()->getIdShipmentMethod());

            $item->setShipment($itemShipment);
        }

        return $cartChangeTransfer->setQuote($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     *
     * @return \Generated\Shared\Transfer\ExpenseTransfer
     */
    protected function createShippingExpenseTransfer(ShipmentTransfer $shipmentTransfer): ExpenseTransfer
    {
        $shipmentExpenseTransfer = new ExpenseTransfer();
        $shipmentExpenseTransfer->fromArray($shipmentTransfer->getMethod()->toArray(), true);
        $shipmentExpenseTransfer->setType(ShipmentsRestApiConfig::SHIPMENT_EXPENSE_TYPE);
        $shipmentExpenseTransfer->setUnitNetPrice(0);
        $shipmentExpenseTransfer->setUnitGrossPrice($shipmentTransfer->getMethod()->getStoreCurrencyPrice());
        $shipmentExpenseTransfer->setQuantity(1);
        $shipmentExpenseTransfer->setShipment($shipmentTransfer);

        return $shipmentExpenseTransfer;
    }
}
