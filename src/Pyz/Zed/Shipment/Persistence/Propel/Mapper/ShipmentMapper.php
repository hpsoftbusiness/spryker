<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
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
        $expenses = $salesOrder->getExpenses();

        if ($expenses->count() > 0) {
            $salesExpense = $salesOrder->getExpenses()[0];
            $storeCurrencyPrice = $salesOrder->getPriceMode() === ShipmentConstants::PRICE_MODE_GROSS ?
                $salesExpense->getGrossPrice() :
                $salesExpense->getNetPrice();
        }

        $methodTransfer = $shipmentTransfer->getMethod()
            ->setCurrencyIsoCode($salesOrder->getCurrencyIsoCode())
            ->setStoreCurrencyPrice($storeCurrencyPrice ?? null);

        return $shipmentTransfer
            ->setMethod($methodTransfer);
    }
}
