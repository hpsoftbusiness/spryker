<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Refund\Business\Processor;

use ArrayObject;
use Generated\Shared\Transfer\ExpenseRefundTransfer;
use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\ItemRefundTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Pyz\Zed\Refund\Business\Exception\RefundProcessingException;
use Pyz\Zed\Refund\Business\Processor\Aggregator\PaymentRefundsAggregatorInterface;
use Pyz\Zed\Refund\Persistence\RefundEntityManagerInterface;
use Pyz\Zed\Refund\RefundConfig;
use Pyz\Zed\Sales\Business\SalesFacadeInterface;

class RefundProcessor implements RefundProcessorInterface
{
    private const EXCEPTION_REFUND_AMOUNT_TOO_HIGH = 'Refund amount is higher than payment amount.';

    private const PROCESSABLE_REFUND_STATUSES = [
        RefundConfig::PAYMENT_REFUND_STATUS_NEW,
        RefundConfig::PAYMENT_REFUND_STATUS_FAILED,
    ];

    /**
     * @var \Pyz\Zed\Sales\Business\SalesFacadeInterface
     */
    private $salesFacade;

    /**
     * @var \Pyz\Zed\Refund\Business\Processor\Aggregator\PaymentRefundsAggregatorInterface
     */
    private $paymentRefundsAggregator;

    /**
     * @var \Pyz\Zed\Refund\Persistence\RefundEntityManagerInterface
     */
    private $entityManager;

    /**
     * @var \Pyz\Zed\Refund\Dependency\Plugin\RefundProcessorPluginInterface[]
     */
    private $processorPlugins;

    /**
     * @param \Pyz\Zed\Sales\Business\SalesFacadeInterface $salesFacade
     * @param \Pyz\Zed\Refund\Business\Processor\Aggregator\PaymentRefundsAggregatorInterface $paymentRefundsAggregator
     * @param \Pyz\Zed\Refund\Persistence\RefundEntityManagerInterface $entityManager
     * @param \Pyz\Zed\Refund\Dependency\Plugin\RefundProcessorPluginInterface[] $processorPlugins
     */
    public function __construct(SalesFacadeInterface $salesFacade, PaymentRefundsAggregatorInterface $paymentRefundsAggregator, RefundEntityManagerInterface $entityManager, array $processorPlugins)
    {
        $this->salesFacade = $salesFacade;
        $this->paymentRefundsAggregator = $paymentRefundsAggregator;
        $this->entityManager = $entityManager;
        $this->processorPlugins = $processorPlugins;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem[] $orderItemEntities
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     *
     * @return void
     */
    public function processRefund(array $orderItemEntities, SpySalesOrder $orderEntity): void
    {
        $orderTransfer = $this->salesFacade->getOrderByIdSalesOrder($orderEntity->getIdSalesOrder());
        [$itemTransfers, $expenseTransfers] = $this->getRelevantItems($orderItemEntities, $orderTransfer);
        $aggregatedRefundTransfers = $this->paymentRefundsAggregator->aggregate(
            $this->collectItemRefunds($itemTransfers),
            $this->collectExpenseRefunds($expenseTransfers),
            $orderTransfer->getPayments()->getArrayCopy()
        );
        $this->assertRefundsAmount($aggregatedRefundTransfers);
        $this->hydrateRefundTransfers(
            $aggregatedRefundTransfers,
            $orderEntity->getIdSalesOrder(),
            $itemTransfers,
            $expenseTransfers
        );
        $this->executeProcessors($aggregatedRefundTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\RefundTransfer[] $refundTransfers
     *
     * @return void
     */
    private function executeProcessors(array $refundTransfers): void
    {
        foreach ($this->processorPlugins as $processorPlugin) {
            $refundResponse = $processorPlugin->processRefunds($refundTransfers);
            if ($refundResponse) {
                $this->persistItemRefunds($refundResponse->getRefunds());
            }
        }
    }

    /**
     * @param iterable|\Generated\Shared\Transfer\RefundTransfer[] $refundTransfers
     *
     * @return void
     */
    private function persistItemRefunds(iterable $refundTransfers): void
    {
        foreach ($refundTransfers as $refundTransfer) {
            $this->saveItemRefunds($refundTransfer->getItemRefunds());
            $this->saveExpenseRefunds($refundTransfer->getExpenseRefunds());
        }
    }

    /**
     * @param iterable|\Generated\Shared\Transfer\ItemRefundTransfer[] $itemRefundTransfers
     *
     * @return void
     */
    private function saveItemRefunds(iterable $itemRefundTransfers): void
    {
        foreach ($itemRefundTransfers as $itemRefundTransfer) {
            $this->entityManager->saveSalesOrderItemRefund($itemRefundTransfer);
        }
    }

    /**
     * @param iterable|\Generated\Shared\Transfer\ExpenseRefundTransfer[] $expenseRefundTransfers
     *
     * @return void
     */
    private function saveExpenseRefunds(iterable $expenseRefundTransfers): void
    {
        foreach ($expenseRefundTransfers as $expenseRefundTransfer) {
            $this->entityManager->saveSalesExpenseRefund($expenseRefundTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\RefundTransfer[] $refundTransfers
     * @param int $idSalesOrder
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     * @param \Generated\Shared\Transfer\ExpenseTransfer[] $expenseTransfers
     *
     * @return void
     */
    private function hydrateRefundTransfers(
        array $refundTransfers,
        int $idSalesOrder,
        array $itemTransfers,
        array $expenseTransfers
    ): void {
        foreach ($refundTransfers as $refundTransfer) {
            $refundTransfer->setFkSalesOrder($idSalesOrder);
            $refundTransfer->setItems(new ArrayObject($itemTransfers));
            $refundTransfer->setExpenses(new ArrayObject($expenseTransfers));
        }
    }

    /**
     * @param \Generated\Shared\Transfer\RefundTransfer[] $refundTransfers
     *
     * @throws \Pyz\Zed\Refund\Business\Exception\RefundProcessingException
     *
     * @return void
     */
    private function assertRefundsAmount(array $refundTransfers): void
    {
        foreach ($refundTransfers as $refundTransfer) {
            if ($refundTransfer->getAmount() > $refundTransfer->getPayment()->getAmount()) {
                throw new RefundProcessingException(self::EXCEPTION_REFUND_AMOUNT_TOO_HIGH);
            }
        }
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem[] $orderItemEntities
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return array
     */
    private function getRelevantItems(array $orderItemEntities, OrderTransfer $orderTransfer): array
    {
        if ($this->isFullOrderRefund($orderItemEntities, $orderTransfer)) {
            $itemTransfers = $orderTransfer->getItems()->getArrayCopy();
        } else {
            $itemTransfers = $this->filterRefundableItems($orderItemEntities, $orderTransfer);
        }

        return [
            $itemTransfers,
            $orderTransfer->getExpenses()->getArrayCopy(),
        ];
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem[] $orderItemEntities
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return array
     */
    private function filterRefundableItems(array $orderItemEntities, OrderTransfer $orderTransfer): array
    {
        $refundableSalesOrderItemIds = array_map(
            function (SpySalesOrderItem $spySalesOrderItem): int {
                return $spySalesOrderItem->getIdSalesOrderItem();
            },
            $orderItemEntities
        );

        $refundableItems = [];
        foreach ($orderTransfer->getItems() as $itemTransfer) {
            if (in_array($itemTransfer->getIdSalesOrderItem(), $refundableSalesOrderItemIds)) {
                $refundableItems[] = $itemTransfer;
            }
        }

        return $refundableItems;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return \Generated\Shared\Transfer\ItemRefundTransfer[]
     */
    private function collectItemRefunds(array $itemTransfers): array
    {
        $itemRefundTransfers = array_reduce(
            $itemTransfers,
            function (array $itemRefundsCollection, ItemTransfer $itemTransfer): array {
                return array_merge($itemRefundsCollection, $itemTransfer->getRefunds()->getArrayCopy());
            },
            []
        );

        return $this->filterProcessableItemRefunds($itemRefundTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\ExpenseTransfer[] $expenseTransfers
     *
     * @return \Generated\Shared\Transfer\ExpenseRefundTransfer[]
     */
    private function collectExpenseRefunds(array $expenseTransfers): array
    {
        $expenseRefundTransfers = array_reduce(
            $expenseTransfers,
            function (array $expenseRefundsCollection, ExpenseTransfer $expenseTransfer): array {
                return array_merge($expenseRefundsCollection, $expenseTransfer->getRefunds()->getArrayCopy());
            },
            []
        );

        return $this->filterProcessableExpenseRefunds($expenseRefundTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemRefundTransfer[] $itemRefundTransfers
     *
     * @return \Generated\Shared\Transfer\ItemRefundTransfer[]
     */
    private function filterProcessableItemRefunds(array $itemRefundTransfers): array
    {
        return array_filter(
            $itemRefundTransfers,
            function (ItemRefundTransfer $itemRefundTransfer): bool {
                return in_array($itemRefundTransfer->getStatus(), self::PROCESSABLE_REFUND_STATUSES);
            }
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ExpenseRefundTransfer[] $expenseRefundTransfers
     *
     * @return \Generated\Shared\Transfer\ExpenseRefundTransfer[]
     */
    private function filterProcessableExpenseRefunds(array $expenseRefundTransfers): array
    {
        return array_filter(
            $expenseRefundTransfers,
            function (ExpenseRefundTransfer $expenseRefundTransfer): bool {
                return in_array($expenseRefundTransfer->getStatus(), self::PROCESSABLE_REFUND_STATUSES);
            }
        );
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem[] $orderItemEntities
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    private function isFullOrderRefund(array $orderItemEntities, OrderTransfer $orderTransfer): bool
    {
        return count($orderItemEntities) === $orderTransfer->getItems()->count();
    }
}
