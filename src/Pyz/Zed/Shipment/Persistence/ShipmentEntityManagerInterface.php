<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Shipment\Persistence;

use Spryker\Zed\Shipment\Persistence\ShipmentEntityManagerInterface as SprykerShipmentEntityManagerInterface;

interface ShipmentEntityManagerInterface extends SprykerShipmentEntityManagerInterface
{
    /**
     * @param int $idShipmentMethod
     * @param int[] $idStores
     *
     * @return void
     */
    public function removeObsoleteDefaultRelationsByShipmenMethodId(int $idShipmentMethod, array $idStores): void;

    /**
     * @param int $idShipmentMethod
     * @param int[] $idStores
     *
     * @return void
     */
    public function cleanDefaultMethodRelationsByShipmentMethodId(int $idShipmentMethod, array $idStores): void;

    /**
     * @param array $idStores
     *
     * @return void
     */
    public function removeDefaultMethodsRelationsInStores(array $idStores): void;

    /**
     * @param int[] $idStores
     * @param int $idShipmentMethod
     *
     * @return void
     */
    public function addDefaultMethodRelationInStores(array $idStores, int $idShipmentMethod): void;
}
