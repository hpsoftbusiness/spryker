<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Shipment\Persistence;

use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Spryker\Zed\Shipment\Persistence\ShipmentRepository as SprykerShipmentRepository;

/**
 * Class ShipmentRepository
 *
 * @package Pyz\Zed\Shipment\Persistence
 *
 * @method \Pyz\Zed\Shipment\Persistence\ShipmentPersistenceFactory getFactory()
 */
class ShipmentRepository extends SprykerShipmentRepository implements ShipmentRepositoryInterface
{
    /**
     * @param int $idShipmentMethod
     *
     * @return \Generated\Shared\Transfer\StoreRelationTransfer
     */
    public function getDefaultInStoresRelationByIdShipmentMethod(int $idShipmentMethod): StoreRelationTransfer
    {
        $shipmentMethodStoreEntities = $this->getFactory()
            ->createShipmentDefaultMethodQuery()
            ->filterByFkShipmentMethod($idShipmentMethod)
            ->leftJoinWithStore()
            ->find();

        $storeRelationTransfer = (new StoreRelationTransfer())->setIdEntity($idShipmentMethod);

        return $this->getFactory()
            ->createPyzStoreRelationMapper()
            ->mapDefaultShipmentMethodEntitiesToStoreRelationTransfer(
                $shipmentMethodStoreEntities,
                $storeRelationTransfer
            );
    }

    /**
     * @param int $storeId
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer|null
     */
    public function getDefaultShipmentMethodByStoreId(int $storeId): ?ShipmentMethodTransfer
    {
        $methodRelationEntity = $this->getFactory()->createShipmentDefaultMethodQuery()->findOneByFkStore($storeId);

        if (!$methodRelationEntity) {
            return $this->findShipmentMethodByIdAndIdStore(2, $storeId);
        }

        return $this->getFactory()
            ->createShipmentMethodMapper()
            ->mapShipmentMethodEntityToShipmentMethodTransfer(
                $methodRelationEntity->getShipmentMethod(),
                new ShipmentMethodTransfer()
            );
    }
}
