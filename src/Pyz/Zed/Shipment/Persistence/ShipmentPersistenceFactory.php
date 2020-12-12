<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Shipment\Persistence;

use Pyz\Zed\Shipment\Persistence\Propel\Mapper\ShipmentMapper as PyzShipmentMapper;
use Spryker\Zed\Shipment\Persistence\Propel\Mapper\ShipmentMapper;
use Spryker\Zed\Shipment\Persistence\ShipmentPersistenceFactory as SprykerShipmentPersistenceFactory;

/**
 * @method \Spryker\Zed\Shipment\ShipmentConfig getConfig()
 * @method \Spryker\Zed\Shipment\Persistence\ShipmentQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Shipment\Persistence\ShipmentEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\Shipment\Persistence\ShipmentRepositoryInterface getRepository()
 */
class ShipmentPersistenceFactory extends SprykerShipmentPersistenceFactory
{
    /**
     * @return \Spryker\Zed\Shipment\Persistence\Propel\Mapper\ShipmentMapper
     */
    public function createShipmentMapper(): ShipmentMapper
    {
        return new PyzShipmentMapper();
    }
}
