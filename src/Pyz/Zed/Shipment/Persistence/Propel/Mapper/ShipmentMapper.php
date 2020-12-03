<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Pyz\Zed\Shipment\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\ShipmentTransfer;
use Orm\Zed\Sales\Persistence\SpySalesShipment;
use Spryker\Shared\Shipment\ShipmentConstants;
use Spryker\Zed\Shipment\Persistence\Propel\Mapper\ShipmentMapper as SprykerShipmentMapper;

class ShipmentMapper extends SprykerShipmentMapper
{
    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesShipment $salesShipmentEntity
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentTransfer
     */
    public function mapShipmentEntityToShipmentTransferWithDetails(
        SpySalesShipment $salesShipmentEntity,
        ShipmentTransfer $shipmentTransfer
    ): ShipmentTransfer {
        $shipmentTransfer = parent::mapShipmentEntityToShipmentTransferWithDetails($salesShipmentEntity, $shipmentTransfer);

        $salesOrder = $salesShipmentEntity->getOrder();
        $salesExpense = $salesOrder->getExpenses()[0];

        $storeCurrencyPrice = $salesOrder->getPriceMode() === ShipmentConstants::PRICE_MODE_GROSS ?
            $salesExpense->getGrossPrice() :
            $salesExpense->getNetPrice();

        $methodTransfer = $shipmentTransfer->getMethod()
            ->setCurrencyIsoCode($salesOrder->getCurrencyIsoCode())
            ->setStoreCurrencyPrice($storeCurrencyPrice);

        return $shipmentTransfer
            ->setMethod($methodTransfer);
    }
}
