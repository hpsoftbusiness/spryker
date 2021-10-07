<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ShipmentGui\Communication\Table;

use Orm\Zed\Shipment\Persistence\Map\PyzShipmentDefaultMethodTableMap;
use Orm\Zed\Shipment\Persistence\Map\SpyShipmentCarrierTableMap;
use Orm\Zed\Shipment\Persistence\Map\SpyShipmentMethodTableMap;
use Orm\Zed\Shipment\Persistence\SpyShipmentMethod;
use Orm\Zed\Store\Persistence\Map\SpyStoreTableMap;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\ShipmentGui\Communication\Table\ShipmentMethodTable as SprykerShipmentMethodTable;

class ShipmentMethodTable extends SprykerShipmentMethodTable
{
    protected const COL_DEFAULT_IN_STORE = 'Actions';
    protected const HEADER_DEFAULT_IN_STORE = 'Default in Store';

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $tableConfiguration
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configureHeader(TableConfiguration $tableConfiguration): TableConfiguration
    {
        $tableConfiguration->setHeader([
            SpyShipmentMethodTableMap::COL_SHIPMENT_METHOD_KEY => static::HEADER_DELIVERY_METHOD_KEY,
            SpyShipmentCarrierTableMap::COL_NAME => static::HEADER_CARRIER_COMPANY,
            SpyShipmentMethodTableMap::COL_NAME => static::HEADER_METHOD_NAME,
            SpyShipmentMethodTableMap::COL_IS_ACTIVE => static::HEADER_STATUS,
            SpyStoreTableMap::COL_NAME => static::HEADER_AVAILABLE_IN_STORE,
            PyzShipmentDefaultMethodTableMap::COL_ID_SHIPMENT_DEFAULT_METHOD_STORE => static::HEADER_DEFAULT_IN_STORE,
            static::COL_ACTIONS => static::HEADER_ACTIONS,
        ]);

        return $tableConfiguration;
    }

    /**
     * @param \Orm\Zed\Shipment\Persistence\SpyShipmentMethod $shipmentMethodEntity
     *
     * @return array
     */
    protected function mapShipmentMethodRow(SpyShipmentMethod $shipmentMethodEntity): array
    {
        return [
            SpyShipmentMethodTableMap::COL_SHIPMENT_METHOD_KEY => $shipmentMethodEntity->getShipmentMethodKey(),
            SpyShipmentCarrierTableMap::COL_NAME => $shipmentMethodEntity->getShipmentCarrier()->getName(),
            SpyShipmentMethodTableMap::COL_NAME => $shipmentMethodEntity->getName(),
            SpyShipmentMethodTableMap::COL_IS_ACTIVE => $this->generateIsActiveLabel($shipmentMethodEntity),
            SpyStoreTableMap::COL_NAME => $this->getStoreNames($shipmentMethodEntity),
            PyzShipmentDefaultMethodTableMap::COL_ID_SHIPMENT_DEFAULT_METHOD_STORE
                => $this->getDefaultInStoresNames($shipmentMethodEntity),
            static::COL_ACTIONS => $this->buildLinks($shipmentMethodEntity),
        ];
    }

    /**
     * @param \Orm\Zed\Shipment\Persistence\SpyShipmentMethod $shipmentMethodEntity
     *
     * @return string
     */
    protected function getDefaultInStoresNames(SpyShipmentMethod $shipmentMethodEntity): string
    {
        $storeNames = [];
        foreach ($shipmentMethodEntity->getShipmentMethodDefaultInStores() as $shipmentMethodStore) {
            $storeNames[] = $this->generateLabel($shipmentMethodStore->getStore()->getName(), 'label-primary');
        }

        return implode(' ', $storeNames);
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $tableConfiguration
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function setRawColumns(TableConfiguration $tableConfiguration): TableConfiguration
    {
        $tableConfiguration->setRawColumns([
            SpyShipmentMethodTableMap::COL_IS_ACTIVE,
            SpyStoreTableMap::COL_NAME,
            PyzShipmentDefaultMethodTableMap::COL_ID_SHIPMENT_DEFAULT_METHOD_STORE,
            static::COL_ACTIONS,
        ]);

        return $tableConfiguration;
    }
}
