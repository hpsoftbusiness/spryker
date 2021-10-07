<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Shipment\Business\Expander;

use Generated\Shared\Transfer\ShipmentMethodsTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Pyz\Zed\Shipment\Business\ShipmentMethod\ShipmentMethodReaderInterface;
use Spryker\Service\Shipment\ShipmentServiceInterface;
use Spryker\Zed\Shipment\Business\Expander\QuoteShipmentExpander as BaseQuoteShipmentExpander;
use Spryker\Zed\Shipment\Business\Mapper\ShipmentMapperInterface;
use Spryker\Zed\Shipment\Business\Sanitizer\ExpenseSanitizerInterface;
use Spryker\Zed\Shipment\Business\ShipmentMethod\MethodReaderInterface;
use Spryker\Zed\Shipment\Dependency\Facade\ShipmentToCalculationFacadeInterface;

class QuoteShipmentExpander extends BaseQuoteShipmentExpander
{
    /**
     * @var \Pyz\Zed\Shipment\Business\ShipmentMethod\ShipmentMethodReaderInterface
     */
    protected $shipmentMethodReader;

    /**
     * @param \Spryker\Service\Shipment\ShipmentServiceInterface $shipmentService
     * @param \Spryker\Zed\Shipment\Business\ShipmentMethod\MethodReaderInterface $methodReader
     * @param \Spryker\Zed\Shipment\Business\Sanitizer\ExpenseSanitizerInterface $expenseSanitizer
     * @param \Spryker\Zed\Shipment\Business\Mapper\ShipmentMapperInterface $shipmentMapper
     * @param \Spryker\Zed\Shipment\Dependency\Facade\ShipmentToCalculationFacadeInterface $calculationFacade
     * @param \Spryker\Zed\ShipmentExtension\Dependency\Plugin\ShipmentGroupsSanitizerPluginInterface[] $shipmentGroupsSanitizers
     * @param \Pyz\Zed\Shipment\Business\ShipmentMethod\ShipmentMethodReaderInterface $shipmentMethodReader
     */
    public function __construct(
        ShipmentServiceInterface $shipmentService,
        MethodReaderInterface $methodReader,
        ExpenseSanitizerInterface $expenseSanitizer,
        ShipmentMapperInterface $shipmentMapper,
        ShipmentToCalculationFacadeInterface $calculationFacade,
        array $shipmentGroupsSanitizers,
        ShipmentMethodReaderInterface $shipmentMethodReader
    ) {
        parent::__construct(
            $shipmentService,
            $methodReader,
            $expenseSanitizer,
            $shipmentMapper,
            $calculationFacade,
            $shipmentGroupsSanitizers
        );

        $this->shipmentMethodReader = $shipmentMethodReader;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodsTransfer $shipmentMethodsTransfer
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer|null
     */
    protected function findShipmentMethod(
        ShipmentMethodsTransfer $shipmentMethodsTransfer,
        ShipmentTransfer $shipmentTransfer
    ): ?ShipmentMethodTransfer {
        $shipmentMethodTransfer = parent::findShipmentMethod($shipmentMethodsTransfer, $shipmentTransfer);

        if ($shipmentMethodTransfer === null) {
            $shipmentMethodTransfer = $this->findDefaultShipmentMethod();
        }

        return $shipmentMethodTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer|null
     */
    protected function findDefaultShipmentMethod(): ?ShipmentMethodTransfer
    {
        $method = $this->shipmentMethodReader->getDefaultShipmentMethod();

        if ($method === null) {
            $method = $this->shipmentMethodReader->getFirstAvailableShipmentMethod();
        }

        return $method;
    }
}
