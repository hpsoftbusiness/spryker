<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Refund\Communication\Plugin;

use ArrayObject;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\RefundDetailCollectionTransfer;
use Pyz\Zed\Refund\RefundConfig;

/**
 * @method \Pyz\Zed\Refund\Business\RefundFacadeInterface getFacade()
 * @method \Pyz\Zed\Refund\Persistence\RefundRepositoryInterface getRepository()
 * @method \Pyz\Zed\Refund\RefundConfig getConfig()
 */
class ItemRefundDetailsCollectorPlugin extends AbstractRefundDetailsCollectorPlugin
{
    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\RefundDetailCollectionTransfer[]
     */
    public function collect(OrderTransfer $orderTransfer): array
    {
        $itemTransfers = $this->filterItemsWithRefunds($orderTransfer->getItems()->getArrayCopy());
        $mappedPayments = $this->mapPaymentsById($orderTransfer->getPayments());
        $refundDetailCollectionTransfers = [];
        foreach ($itemTransfers as $itemTransfer) {
            $refundDetailCollectionTransfers[] = $this->createItemRefundDetailsTransfers($itemTransfer, $mappedPayments);
        }

        return $refundDetailCollectionTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    private function filterItemsWithRefunds(array $itemTransfers): array
    {
        return array_filter(
            $itemTransfers,
            function (ItemTransfer $itemTransfer): bool {
                return $itemTransfer->getRefunds()->count() > 0;
            }
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\PaymentTransfer[] $mappedPayments
     *
     * @return \Generated\Shared\Transfer\RefundDetailCollectionTransfer
     */
    private function createItemRefundDetailsTransfers(ItemTransfer $itemTransfer, array $mappedPayments): RefundDetailCollectionTransfer
    {
        $refundDetailCollectionTransfer = new RefundDetailCollectionTransfer();
        $refundDetailCollectionTransfer->setName($itemTransfer->getName());
        $refundDetailCollectionTransfer->setType(RefundConfig::REFUND_DETAIL_TYPE_ITEM);
        $refundDetailCollectionTransfer->setId($itemTransfer->getIdSalesOrderItem());
        $refundDetailTransfers = $this->mapRefundsToDetailTransfers($itemTransfer->getRefunds(), $mappedPayments);
        $refundDetailCollectionTransfer->setRefundDetails(new ArrayObject($refundDetailTransfers));

        return $refundDetailCollectionTransfer;
    }
}
