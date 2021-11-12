<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Shipment\Business\ShipmentMethod;

use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Spryker\Zed\Shipment\Business\Model\MethodPriceInterface;
use Spryker\Zed\Shipment\Business\ShipmentMethod\ShipmentMethodCreator as SprykerShipmentMethodCreator;
use Spryker\Zed\Shipment\Business\ShipmentMethod\ShipmentMethodStoreRelationUpdaterInterface;
use Spryker\Zed\Shipment\Persistence\ShipmentEntityManagerInterface;

class ShipmentMethodCreator extends SprykerShipmentMethodCreator
{
    /**
     * @var \Pyz\Zed\Shipment\Business\ShipmentMethod\ShipmentMethodDefaultInStoresRelationUpdaterInterface
     */
    protected $defaultInStoresRelationUpdater;

    /**
     * @param \Spryker\Zed\Shipment\Persistence\ShipmentEntityManagerInterface $shipmentEntityManager
     * @param \Spryker\Zed\Shipment\Business\Model\MethodPriceInterface $methodPrice
     * @param \Spryker\Zed\Shipment\Business\ShipmentMethod\ShipmentMethodStoreRelationUpdaterInterface $storeRelationUpdater
     * @param \Pyz\Zed\Shipment\Business\ShipmentMethod\ShipmentMethodDefaultInStoresRelationUpdaterInterface $defaultInStoresRelationUpdater
     */
    public function __construct(
        ShipmentEntityManagerInterface $shipmentEntityManager,
        MethodPriceInterface $methodPrice,
        ShipmentMethodStoreRelationUpdaterInterface $storeRelationUpdater,
        ShipmentMethodDefaultInStoresRelationUpdaterInterface $defaultInStoresRelationUpdater
    ) {
        parent::__construct($shipmentEntityManager, $methodPrice, $storeRelationUpdater);
        $this->defaultInStoresRelationUpdater = $defaultInStoresRelationUpdater;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $shipmentMethodTransfer
     *
     * @return int|null
     */
    protected function executeCreateShipmentMethodTransaction(ShipmentMethodTransfer $shipmentMethodTransfer): ?int
    {
        $idShipmentMethod = parent::executeCreateShipmentMethodTransaction($shipmentMethodTransfer);
        if ($idShipmentMethod === null) {
            return null;
        }

        $shipmentMethodTransfer
            ->requireDefaultInStoresRelation()
            ->getDefaultInStoresRelation()->setIdEntity($idShipmentMethod);

        $inStoresIds = $shipmentMethodTransfer->getStoreRelation()->getIdStores();
        $defaultInStoresIds = $shipmentMethodTransfer->getDefaultInStoresRelation()->getIdStores();

        $updatedList = array_intersect($defaultInStoresIds, $inStoresIds);

        $shipmentMethodTransfer->getDefaultInStoresRelation()->setIdStores($updatedList);
        $this->defaultInStoresRelationUpdater->update($shipmentMethodTransfer->getDefaultInStoresRelation());

        return $idShipmentMethod;
    }
}
