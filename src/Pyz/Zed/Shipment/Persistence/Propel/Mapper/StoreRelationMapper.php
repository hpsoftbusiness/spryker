<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Shipment\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\StoreRelationTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\Shipment\Persistence\Propel\Mapper\StoreRelationMapper as SprykerStoreRelationMapper;

class StoreRelationMapper extends SprykerStoreRelationMapper
{
    /**
     * @param \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\Shipment\Persistence\PyzShipmentDefaultMethod[] $shipmentMethodStoreEntities
     * @param \Generated\Shared\Transfer\StoreRelationTransfer $storeRelationTransfer
     *
     * @return \Generated\Shared\Transfer\StoreRelationTransfer
     */
    public function mapDefaultShipmentMethodEntitiesToStoreRelationTransfer(
        ObjectCollection $shipmentMethodStoreEntities,
        StoreRelationTransfer $storeRelationTransfer
    ): StoreRelationTransfer {
        foreach ($shipmentMethodStoreEntities as $shipmentMethodStoreEntity) {
            $storeRelationTransfer->addStores($this->mapStoreEntityToStoreTransfer($shipmentMethodStoreEntity->getStore(), new StoreTransfer()));
            $storeRelationTransfer->addIdStores($shipmentMethodStoreEntity->getFkStore());
        }

        return $storeRelationTransfer;
    }
}
