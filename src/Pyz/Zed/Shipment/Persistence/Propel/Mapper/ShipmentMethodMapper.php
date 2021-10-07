<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Shipment\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Orm\Zed\Shipment\Persistence\SpyShipmentMethod;
use Spryker\Zed\Shipment\Persistence\Propel\Mapper\ShipmentMethodMapper as SprykerShipmentMethodMapper;

class ShipmentMethodMapper extends SprykerShipmentMethodMapper
{
    /**
     * @var \Pyz\Zed\Shipment\Persistence\Propel\Mapper\StoreRelationMapper
     */
    protected $storeRelationMapper;

    /**
     * @param \Pyz\Zed\Shipment\Persistence\Propel\Mapper\StoreRelationMapper $storeRelationMapper
     */
    public function __construct(
        StoreRelationMapper $storeRelationMapper
    ) {
        parent::__construct($storeRelationMapper);
    }

    /**
     * @param \Orm\Zed\Shipment\Persistence\SpyShipmentMethod $salesShipmentMethodEntity
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $shipmentMethodTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer
     */
    public function mapShipmentMethodEntityToShipmentMethodTransferWithPrices(
        SpyShipmentMethod $salesShipmentMethodEntity,
        ShipmentMethodTransfer $shipmentMethodTransfer
    ): ShipmentMethodTransfer {
        $shipmentMethodTransfer = parent::mapShipmentMethodEntityToShipmentMethodTransferWithPrices(
            $salesShipmentMethodEntity,
            $shipmentMethodTransfer
        );

        $storeRelationTransfer = new StoreRelationTransfer();
        $storeRelationTransfer->setIdEntity($salesShipmentMethodEntity->getIdShipmentMethod());
        $shipmentMethodTransfer->setDefaultInStoresRelation(
            $this->storeRelationMapper->mapDefaultShipmentMethodEntitiesToStoreRelationTransfer(
                $salesShipmentMethodEntity->getShipmentMethodDefaultInStores(),
                $storeRelationTransfer
            )
        );

        return $shipmentMethodTransfer;
    }
}
