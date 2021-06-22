<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Adyen\Communication\Plugin\Refund;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\RefundResponseTransfer;
use Generated\Shared\Transfer\RefundTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Pyz\Zed\Refund\Dependency\Plugin\RefundProcessorPluginInterface;
use Pyz\Zed\Refund\RefundConfig;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use SprykerEco\Shared\Adyen\AdyenConfig;

/**
 * @method \Pyz\Zed\Adyen\Communication\AdyenCommunicationFactory getFactory()
 * @method \Pyz\Zed\Adyen\Business\AdyenFacadeInterface getFacade()
 * @method \Pyz\Zed\Adyen\AdyenConfig getConfig()
 * @method \SprykerEco\Zed\Adyen\Persistence\AdyenRepositoryInterface getRepository()
 */
class AdyenRefundProcessorPlugin extends AbstractPlugin implements RefundProcessorPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\RefundTransfer[] $refundTransfers
     *
     * @return \Generated\Shared\Transfer\RefundResponseTransfer|null
     */
    public function processRefunds(array $refundTransfers): ?RefundResponseTransfer
    {
        $adyenRefundTransfer = $this->findAdyenRefundTransfer($refundTransfers);
        if (!$adyenRefundTransfer) {
            return null;
        }

        $orderTransfer = $this->getOrderTransfer($adyenRefundTransfer->getFkSalesOrder());
        $salesOrderItemEntities = $this->mapRefundItemsToEntities($adyenRefundTransfer->getItems());
        $this->getFacade()->executeRefundCommand($salesOrderItemEntities, $orderTransfer, $adyenRefundTransfer);

        $refundResponseTransfer = (new RefundResponseTransfer());
        $refundResponseTransfer->addRefund($adyenRefundTransfer);
        $this->setRefundResponseStatus($refundResponseTransfer, $this->assertRefundRequestWasProcessed($salesOrderItemEntities));

        return $refundResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\RefundResponseTransfer $refundResponseTransfer
     * @param bool $isSuccess
     *
     * @return void
     */
    private function setRefundResponseStatus(RefundResponseTransfer $refundResponseTransfer, bool $isSuccess): void
    {
        $refundResponseTransfer->setIsSuccess($isSuccess);
        $status = $isSuccess ? RefundConfig::PAYMENT_REFUND_STATUS_PENDING : RefundConfig::PAYMENT_REFUND_STATUS_FAILED;
        $this->setItemRefundsStatus($refundResponseTransfer->getRefunds()[0], $status);
        $this->setExpenseRefundsStatus($refundResponseTransfer->getRefunds()[0], $status);
    }

    /**
     * @param \Generated\Shared\Transfer\RefundTransfer $refundTransfer
     * @param string $status
     *
     * @return void
     */
    private function setItemRefundsStatus(RefundTransfer $refundTransfer, string $status): void
    {
        foreach ($refundTransfer->getItemRefunds() as $itemRefund) {
            $itemRefund->setStatus($status);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\RefundTransfer $refundTransfer
     * @param string $status
     *
     * @return void
     */
    private function setExpenseRefundsStatus(RefundTransfer $refundTransfer, string $status): void
    {
        foreach ($refundTransfer->getExpenseRefunds() as $expenseRefund) {
            $expenseRefund->setStatus($status);
        }
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem[] $salesOrderItemEntities
     *
     * @return bool
     */
    private function assertRefundRequestWasProcessed(array $salesOrderItemEntities): bool
    {
        $spySalesOrderItemIds = $this->getSalesOrderItemIds($salesOrderItemEntities);
        $adyenOrderItemTransfers = $this->getRepository()->getOrderItemsByIdsSalesOrderItems($spySalesOrderItemIds);
        foreach ($adyenOrderItemTransfers as $adyenOrderItemTransfer) {
            if ($adyenOrderItemTransfer->getStatus() !== $this->getConfig()->getOmsStatusRefundPending()) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem[] $salesOrderItemEntities
     *
     * @return int[]
     */
    private function getSalesOrderItemIds(array $salesOrderItemEntities): array
    {
        return array_map(
            static function (SpySalesOrderItem $spySalesOrderItem): int {
                return $spySalesOrderItem->getIdSalesOrderItem();
            },
            $salesOrderItemEntities
        );
    }

    /**
     * @param \Generated\Shared\Transfer\RefundTransfer[] $refundTransfers
     *
     * @return \Generated\Shared\Transfer\RefundTransfer|null
     */
    private function findAdyenRefundTransfer(array $refundTransfers): ?RefundTransfer
    {
        foreach ($refundTransfers as $refundTransfer) {
            if ($refundTransfer->getPayment()->getPaymentProvider() === AdyenConfig::PROVIDER_NAME) {
                return $refundTransfer;
            }
        }

        return null;
    }

    /**
     * @param int $idSalesOrder
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    private function getOrderTransfer(int $idSalesOrder): OrderTransfer
    {
        return $this
            ->getFactory()
            ->getSalesFacade()
            ->getOrderByIdSalesOrder($idSalesOrder);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[]|iterable $itemTransfers
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItem[]
     */
    private function mapRefundItemsToEntities(iterable $itemTransfers): array
    {
        $salesOrderItems = [];
        foreach ($itemTransfers as $itemTransfer) {
            $salesOrderItemEntity = new SpySalesOrderItem();
            $salesOrderItemEntity->fromArray($itemTransfer->toArray());
            $salesOrderItems[] = $salesOrderItemEntity;
        }

        return $salesOrderItems;
    }
}
