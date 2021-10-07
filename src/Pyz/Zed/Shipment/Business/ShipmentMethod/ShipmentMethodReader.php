<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Shipment\Business\ShipmentMethod;

use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Pyz\Zed\Shipment\Persistence\ShipmentRepositoryInterface;
use Spryker\Zed\Shipment\Business\ShipmentMethod\ShipmentMethodReader as SprykerShipmentMethodReader;
use Spryker\Zed\Shipment\Dependency\Facade\ShipmentToCurrencyInterface;
use Spryker\Zed\Shipment\Dependency\Facade\ShipmentToStoreInterface;

/**
 * Class ShipmentMethodReader
 *
 * @package Pyz\Zed\Shipment\Business\ShipmentMethod
 *
 * @property \Pyz\Zed\Shipment\Persistence\ShipmentRepositoryInterface $shipmentRepository
 */
class ShipmentMethodReader extends SprykerShipmentMethodReader implements ShipmentMethodReaderInterface
{
    /**
     * @var \Spryker\Zed\Shipment\Dependency\Facade\ShipmentToStoreInterface
     */
    protected $storeFacade;

    /**
     * @param \Pyz\Zed\Shipment\Persistence\ShipmentRepositoryInterface $shipmentRepository
     * @param \Spryker\Zed\Shipment\Dependency\Facade\ShipmentToCurrencyInterface $currencyFacade
     * @param \Spryker\Zed\Shipment\Dependency\Facade\ShipmentToStoreInterface $storeFacade
     */
    public function __construct(
        ShipmentRepositoryInterface $shipmentRepository,
        ShipmentToCurrencyInterface $currencyFacade,
        ShipmentToStoreInterface $storeFacade
    ) {
        parent::__construct($shipmentRepository, $currencyFacade);
        $this->storeFacade = $storeFacade;
    }

    /**
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer
     */
    public function fetchDefaultOrFirstAvailableShipmentMethod(): ShipmentMethodTransfer
    {
        return $this->getDefaultShipmentMethod()
            ?: $this->getFirstAvailableShipmentMethod()
            ?: new ShipmentMethodTransfer();
    }

    /**
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer|null
     */
    public function getDefaultShipmentMethod(): ?ShipmentMethodTransfer
    {
        $storeId = $this->storeFacade->getCurrentStore()->getIdStore();

        return $this->shipmentRepository->getDefaultShipmentMethodByStoreId($storeId);
    }

    /**
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer|null
     */
    public function getFirstAvailableShipmentMethod(): ?ShipmentMethodTransfer
    {
        $storeId = $this->storeFacade->getCurrentStore()->getIdStore();
        $methods = $this->shipmentRepository->getActiveShipmentMethodsForStore($storeId);

        return current($methods);
    }
}
