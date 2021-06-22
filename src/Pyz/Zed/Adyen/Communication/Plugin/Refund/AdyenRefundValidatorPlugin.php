<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Adyen\Communication\Plugin\Refund;

use ArrayObject;
use Generated\Shared\Transfer\ItemRefundTransfer;
use Generated\Shared\Transfer\RefundTransfer;
use Pyz\Shared\Adyen\AdyenConfig as SharedAdyenConfig;
use Pyz\Zed\Adyen\AdyenConfig;
use Pyz\Zed\Refund\Business\Exception\RefundValidatingException;
use Pyz\Zed\Refund\Dependency\Plugin\RefundValidatorPluginInterface;
use Pyz\Zed\Refund\RefundConfig;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Pyz\Zed\Adyen\Communication\AdyenCommunicationFactory getFactory()
 * @method \Pyz\Zed\Adyen\Business\AdyenFacadeInterface getFacade()
 * @method \Pyz\Zed\Adyen\AdyenConfig getConfig()
 * @method \SprykerEco\Zed\Adyen\Persistence\AdyenRepositoryInterface getRepository()
 */
class AdyenRefundValidatorPlugin extends AbstractPlugin implements RefundValidatorPluginInterface
{
    private const EXCEPTION_ADYEN_ITEM_PAYMENT_NOT_FOUND = 'Adyen payment for order item ID %d not found.';
    private const EXCEPTION_ADYEN_ITEM_PAYMENT_NOT_IN_REFUND_PROCESS = 'Adyen order item ID %d is not in refund process.';

    /**
     * @return string
     */
    public function getApplicablePaymentProvider(): string
    {
        return SharedAdyenConfig::PROVIDER_NAME;
    }

    /**
     * @param \Generated\Shared\Transfer\RefundTransfer $refundTransfer
     *
     * @return \Generated\Shared\Transfer\RefundTransfer
     */
    public function validate(RefundTransfer $refundTransfer): RefundTransfer
    {
        $itemRefundTransfers = $refundTransfer->getItemRefunds()->getArrayCopy();
        $salesOrderItemsIds = $this->collectItemIds($itemRefundTransfers);
        $adyenOrderItemTransfers = $this->getRepository()->getOrderItemsByIdsSalesOrderItems($salesOrderItemsIds);
        $orderItemIdToAdyenRefundStatusMap = $this->getItemRefundStatusMap($adyenOrderItemTransfers);
        $this->updateItemRefundsStatus($itemRefundTransfers, $orderItemIdToAdyenRefundStatusMap);
        $expenseRefundTransfers = $refundTransfer->getExpenseRefunds()->getArrayCopy();
        $this->updateExpenseRefundsStatus(
            $expenseRefundTransfers,
            $this->getAggregatedItemRefundStatus($itemRefundTransfers)
        );
        $refundTransfer->setItemRefunds(new ArrayObject($itemRefundTransfers));
        $refundTransfer->setExpenseRefunds(new ArrayObject($expenseRefundTransfers));

        return $refundTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemRefundTransfer[] $itemRefundTransfers
     * @param string[] $adyenRefundStatusMap
     *
     * @throws \Pyz\Zed\Refund\Business\Exception\RefundValidatingException
     *
     * @return void
     */
    private function updateItemRefundsStatus(array $itemRefundTransfers, array $adyenRefundStatusMap): void
    {
        foreach ($itemRefundTransfers as $itemRefundTransfer) {
            $adyenRefundStatus = $adyenRefundStatusMap[$itemRefundTransfer->getFkSalesOrderItem()] ?? null;
            if (!$adyenRefundStatus) {
                throw new RefundValidatingException(
                    sprintf(
                        self::EXCEPTION_ADYEN_ITEM_PAYMENT_NOT_FOUND,
                        $itemRefundTransfer->getFkSalesOrderItem()
                    )
                );
            }

            $itemRefundStatus = $this->getItemRefundStatusByAdyenPaymentStatus($adyenRefundStatus);
            if (!$itemRefundStatus) {
                throw new RefundValidatingException(
                    sprintf(
                        self::EXCEPTION_ADYEN_ITEM_PAYMENT_NOT_IN_REFUND_PROCESS,
                        $itemRefundTransfer->getFkSalesOrderItem()
                    )
                );
            }

            $itemRefundTransfer->setStatus($itemRefundStatus);
        }
    }

    /**
     * Specification:
     * - Expense payments doesn't have payment entries in Adyen order items table, so we're determining whether the
     * expense refund was successful based on item refund status.
     *
     * @param \Generated\Shared\Transfer\ExpenseRefundTransfer[] $expenseRefundTransfers
     * @param string $aggregatedItemRefundStatus
     *
     * @return void
     */
    private function updateExpenseRefundsStatus(array $expenseRefundTransfers, string $aggregatedItemRefundStatus): void
    {
        foreach ($expenseRefundTransfers as $expenseRefundTransfer) {
            $expenseRefundTransfer->setStatus($aggregatedItemRefundStatus);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ItemRefundTransfer[] $itemRefundTransfers
     *
     * @return string
     */
    private function getAggregatedItemRefundStatus(array $itemRefundTransfers): string
    {
        foreach ($itemRefundTransfers as $itemRefundTransfer) {
            if ($itemRefundTransfer->getStatus() === RefundConfig::PAYMENT_REFUND_STATUS_FAILED) {
                return RefundConfig::PAYMENT_REFUND_STATUS_FAILED;
            }

            if ($itemRefundTransfer->getStatus() === RefundConfig::PAYMENT_REFUND_STATUS_PENDING) {
                return RefundConfig::PAYMENT_REFUND_STATUS_PENDING;
            }
        }

        return RefundConfig::PAYMENT_REFUND_STATUS_PROCESSED;
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentAdyenOrderItemTransfer[] $adyenOrderItemTransfers
     *
     * @return string[]
     */
    private function getItemRefundStatusMap(array $adyenOrderItemTransfers): array
    {
        $salesOrderIdToRefundStatusMap = [];
        foreach ($adyenOrderItemTransfers as $adyenOrderItemTransfer) {
            $salesOrderIdToRefundStatusMap[$adyenOrderItemTransfer->getFkSalesOrderItem()] = $adyenOrderItemTransfer->getStatus();
        }

        return $salesOrderIdToRefundStatusMap;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemRefundTransfer[] $itemRefunds
     *
     * @return int[]
     */
    private function collectItemIds(array $itemRefunds): array
    {
        return array_map(
            function (ItemRefundTransfer $itemRefundTransfer): int {
                return $itemRefundTransfer->getFkSalesOrderItem();
            },
            $itemRefunds
        );
    }

    /**
     * @param string $adyenStatus
     *
     * @return string|null
     */
    private function getItemRefundStatusByAdyenPaymentStatus(string $adyenStatus): ?string
    {
        return AdyenConfig::PAYMENT_TO_REFUND_STATUS_MAP[$adyenStatus] ?? null;
    }
}
