<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Refund\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\ItemRefundTransfer;
use Orm\Zed\Refund\Persistence\PyzSalesOrderItemRefund;
use Propel\Runtime\Collection\Collection;

class ItemRefundMapper
{
    /**
     * @param \Propel\Runtime\Collection\Collection $pyzSalesOrderItemRefunds
     *
     * @return \Generated\Shared\Transfer\ItemRefundTransfer[]
     */
    public function mapItemRefundEntityCollectionToTransfers(Collection $pyzSalesOrderItemRefunds): array
    {
        $itemRefundTransfers = [];
        foreach ($pyzSalesOrderItemRefunds as $pyzSalesOrderItemRefund) {
            $itemRefundTransfers[] = $this->mapItemRefundEntityToTransfer($pyzSalesOrderItemRefund, new ItemRefundTransfer());
        }

        return $itemRefundTransfers;
    }

    /**
     * @param \Orm\Zed\Refund\Persistence\PyzSalesOrderItemRefund $pyzSalesOrderItemRefund
     * @param \Generated\Shared\Transfer\ItemRefundTransfer $itemRefundTransfer
     *
     * @return \Generated\Shared\Transfer\ItemRefundTransfer
     */
    public function mapItemRefundEntityToTransfer(
        PyzSalesOrderItemRefund $pyzSalesOrderItemRefund,
        ItemRefundTransfer $itemRefundTransfer
    ): ItemRefundTransfer {
        return $itemRefundTransfer->fromArray($pyzSalesOrderItemRefund->toArray(), true);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemRefundTransfer $itemRefundTransfer
     * @param \Orm\Zed\Refund\Persistence\PyzSalesOrderItemRefund $pyzSalesOrderItemRefund
     *
     * @return \Orm\Zed\Refund\Persistence\PyzSalesOrderItemRefund
     */
    public function mapItemRefundTransferToEntity(
        ItemRefundTransfer $itemRefundTransfer,
        PyzSalesOrderItemRefund $pyzSalesOrderItemRefund
    ): PyzSalesOrderItemRefund {
        $pyzSalesOrderItemRefund->fromArray($itemRefundTransfer->toArray());

        return $pyzSalesOrderItemRefund;
    }
}
