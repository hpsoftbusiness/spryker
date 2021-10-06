<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Shipment\Business\ShipmentMethod;

use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Spryker\Zed\Shipment\Business\Model\MethodPriceInterface;
use Spryker\Zed\Shipment\Business\ShipmentMethod\ShipmentMethodStoreRelationUpdaterInterface;
use Spryker\Zed\Shipment\Business\ShipmentMethod\ShipmentMethodUpdater as SprykerShipmentMethodUpdater;
use Spryker\Zed\Shipment\Persistence\ShipmentEntityManagerInterface;
use Spryker\Zed\Shipment\Persistence\ShipmentRepositoryInterface;

class ShipmentMethodUpdater extends SprykerShipmentMethodUpdater
{
    /**
     * @var \Pyz\Zed\Shipment\Business\ShipmentMethod\ShipmentMethodDefaultInStoresRelationUpdaterInterface
     */
    protected $defaultInStoresRelationUpdater;

    /**
     * @param \Spryker\Zed\Shipment\Persistence\ShipmentRepositoryInterface $shipmentRepository
     * @param \Spryker\Zed\Shipment\Persistence\ShipmentEntityManagerInterface $shipmentEntityManager
     * @param \Spryker\Zed\Shipment\Business\Model\MethodPriceInterface $methodPrice
     * @param \Spryker\Zed\Shipment\Business\ShipmentMethod\ShipmentMethodStoreRelationUpdaterInterface $storeRelationUpdater
     * @param \Pyz\Zed\Shipment\Business\ShipmentMethod\ShipmentMethodDefaultInStoresRelationUpdaterInterface $defaultInStoresRelationUpdater
     */
    public function __construct(
        ShipmentRepositoryInterface $shipmentRepository,
        ShipmentEntityManagerInterface $shipmentEntityManager,
        MethodPriceInterface $methodPrice,
        ShipmentMethodStoreRelationUpdaterInterface $storeRelationUpdater,
        ShipmentMethodDefaultInStoresRelationUpdaterInterface $defaultInStoresRelationUpdater
    ) {
        parent::__construct($shipmentRepository, $shipmentEntityManager, $methodPrice, $storeRelationUpdater);
        $this->defaultInStoresRelationUpdater = $defaultInStoresRelationUpdater;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $shipmentMethodTransfer
     *
     * @return bool
     */
    protected function executeUpdateShipmentMethodTransaction(ShipmentMethodTransfer $shipmentMethodTransfer): bool
    {
        if (parent::executeUpdateShipmentMethodTransaction($shipmentMethodTransfer) === false) {
            return false;
        }

        $idShipmentMethod = $shipmentMethodTransfer->getIdShipmentMethod();

        $shipmentMethodTransfer
            ->requireDefaultInStoresRelation()
            ->getDefaultInStoresRelation()->setIdEntity($idShipmentMethod);

        $inStoresIds = $shipmentMethodTransfer->getStoreRelation()->getIdStores();
        $defaultInStoresIds = $shipmentMethodTransfer->getDefaultInStoresRelation()->getIdStores();

        $updatedList = array_intersect($defaultInStoresIds, $inStoresIds);

        $shipmentMethodTransfer->getDefaultInStoresRelation()->setIdStores($updatedList);
        $this->defaultInStoresRelationUpdater->update($shipmentMethodTransfer->getDefaultInStoresRelation());

        return true;
    }
}
