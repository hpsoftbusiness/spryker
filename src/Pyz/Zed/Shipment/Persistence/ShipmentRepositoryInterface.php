<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Shipment\Persistence;

use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Spryker\Zed\Shipment\Persistence\ShipmentRepositoryInterface as SprykerShipmentRepositoryInterface;

interface ShipmentRepositoryInterface extends SprykerShipmentRepositoryInterface
{
    /**
     * @param int $idShipmentMethod
     *
     * @return \Generated\Shared\Transfer\StoreRelationTransfer
     */
    public function getDefaultInStoresRelationByIdShipmentMethod(int $idShipmentMethod): StoreRelationTransfer;

    /**
     * @param int $storeId
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer|null
     */
    public function getDefaultShipmentMethodByStoreId(int $storeId): ?ShipmentMethodTransfer;
}
