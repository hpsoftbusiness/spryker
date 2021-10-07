<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Shipment\Persistence;

use Orm\Zed\Shipment\Persistence\PyzShipmentDefaultMethod;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Shipment\Persistence\ShipmentEntityManager as SprykerShipmentEntityManager;

/**
 * Class ShipmentEntityManager
 *
 * @package Pyz\Zed\Shipment\Persistence
 *
 * @method \Pyz\Zed\Shipment\Persistence\ShipmentPersistenceFactory getFactory()
 */
class ShipmentEntityManager extends SprykerShipmentEntityManager implements ShipmentEntityManagerInterface
{
    /**
     * @param array $idStores
     *
     * @return void
     */
    public function removeDefaultMethodsRelationsInStores(array $idStores): void
    {
        if ($idStores === []) {
            return;
        }

        $this->getFactory()
            ->createShipmentDefaultMethodQuery()
            ->filterByFkStore_In($idStores)
            ->delete();
    }

    /**
     * @param array $idStores
     * @param int $idShipmentMethod
     *
     * @return void
     */
    public function addDefaultMethodRelationInStores(array $idStores, int $idShipmentMethod): void
    {
        foreach ($idStores as $idStore) {
            $shipmentMethodStoreEntity = new PyzShipmentDefaultMethod();
            $shipmentMethodStoreEntity->setFkStore($idStore)
                ->setFkShipmentMethod($idShipmentMethod)
                ->save();
        }
    }

    /**
     * @param int $idShipmentMethod
     * @param int[] $idStores
     *
     * @return void
     */
    public function removeObsoleteDefaultRelationsByShipmenMethodId(int $idShipmentMethod, array $idStores): void
    {
         $this->getFactory()
            ->createShipmentDefaultMethodQuery()
            ->filterByFkShipmentMethod($idShipmentMethod)
            ->filterByFkStore_In($idStores)
            ->delete();
    }

    /**
     * @param int $idShipmentMethod
     * @param array $idStores
     *
     * @return void
     */
    public function cleanDefaultMethodRelationsByShipmentMethodId(int $idShipmentMethod, array $idStores): void
    {
        $query = $this->getFactory()
            ->createShipmentDefaultMethodQuery()
            ->filterByFkShipmentMethod($idShipmentMethod)
            ->filterByFkStore($idStores, Criteria::NOT_IN)
            ->delete();
    }
}
