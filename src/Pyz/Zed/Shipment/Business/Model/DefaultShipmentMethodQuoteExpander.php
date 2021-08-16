<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Shipment\Business\Model;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Spryker\Zed\Shipment\Persistence\ShipmentRepositoryInterface;

class DefaultShipmentMethodQuoteExpander implements DefaultShipmentMethodQuoteExpanderInterface
{
    protected const DEFAULT_SHIPMENT_METHOD_KEY = 'myworld_standard_shipment_method';
    /**
     * @var \Spryker\Zed\Shipment\Persistence\ShipmentRepositoryInterface
     */
    protected $shipmentRepository;

    /**
     * @param \Spryker\Zed\Shipment\Persistence\ShipmentRepositoryInterface $shipmentRepository
     */
    public function __construct(ShipmentRepositoryInterface $shipmentRepository)
    {
        $this->shipmentRepository = $shipmentRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function expand(QuoteTransfer $quoteTransfer): void
    {
        $methods = $this->shipmentRepository->getActiveShipmentMethods();

        /** @var \Generated\Shared\Transfer\ShipmentMethodTransfer $method */
        foreach ($methods as $method) {
            if ($method->getShipmentMethodKey() === self::DEFAULT_SHIPMENT_METHOD_KEY) {
                $shipmentTransfer = new ShipmentTransfer();
                $shipmentTransfer->setMethod($method);
                $quoteTransfer->setShipment($shipmentTransfer);
            }
        }
    }
}
