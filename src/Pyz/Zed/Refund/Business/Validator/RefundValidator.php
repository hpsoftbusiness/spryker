<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Refund\Business\Validator;

use ArrayObject;
use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\RefundTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Pyz\Zed\Refund\Business\Exception\RefundValidatingException;
use Pyz\Zed\Refund\Persistence\RefundEntityManagerInterface;
use Pyz\Zed\Refund\RefundConfig;
use Pyz\Zed\Sales\Business\SalesFacadeInterface;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;

class RefundValidator implements RefundValidatorInterface
{
    private const EXCEPTION_NOT_FOUND_PAYMENT_METHOD_NAME = 'Payment method name for refund was not found.';

    /**
     * @var \Pyz\Zed\Sales\Business\SalesFacadeInterface
     */
    private $salesFacade;

    /**
     * @var \Pyz\Zed\Refund\Persistence\RefundEntityManagerInterface
     */
    private $entityManager;

    /**
     * @var \Pyz\Zed\Refund\Dependency\Plugin\RefundValidatorPluginInterface[]
     */
    private $validatorPlugins;

    /**
     * @param \Pyz\Zed\Sales\Business\SalesFacadeInterface $salesFacade
     * @param \Pyz\Zed\Refund\Persistence\RefundEntityManagerInterface $entityManager
     * @param \Pyz\Zed\Refund\Dependency\Plugin\RefundValidatorPluginInterface[] $validatorPlugins
     */
    public function __construct(
        SalesFacadeInterface $salesFacade,
        RefundEntityManagerInterface $entityManager,
        array $validatorPlugins
    ) {
        $this->salesFacade = $salesFacade;
        $this->entityManager = $entityManager;
        $this->validatorPlugins = $validatorPlugins;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem[] $orderItems
     * @param int $idSalesOrder
     *
     * @return void
     */
    public function validate(array $orderItems, int $idSalesOrder): void
    {
        $orderTransfer = $this->salesFacade->getOrderByIdSalesOrder($idSalesOrder);
        $itemTransfers = $orderTransfer->getItems()->getArrayCopy();
        if (count($itemTransfers) !== count($orderItems)) {
            $relevantItemIds = $this->collectItemIds($orderItems);
            $itemTransfers = $this->filterRelevantItems($itemTransfers, $relevantItemIds);
        }

        $paymentMap = $this->getPaymentMethodIdToProviderMap($orderTransfer->getPayments());
        $itemRefunds = $this->getItemPendingRefunds($itemTransfers, $paymentMap);
        $expenseRefunds = $this->getExpensePendingRefunds($orderTransfer->getExpenses()->getArrayCopy(), $paymentMap);

        $this->validateRefunds($idSalesOrder, $itemRefunds, $expenseRefunds);
    }

    /**
     * @param int $idSalesOrder
     * @param \Generated\Shared\Transfer\ItemRefundTransfer[][] $itemRefunds
     * @param \Generated\Shared\Transfer\ExpenseRefundTransfer[][] $expenseRefunds
     *
     * @return void
     */
    private function validateRefunds(int $idSalesOrder, array $itemRefunds, array $expenseRefunds): void
    {
        $refundedPaymentProviders = $this->getUsedPaymentProviders($itemRefunds, $expenseRefunds);

        foreach ($this->validatorPlugins as $validatorPlugin) {
            $applicablePaymentProvider = $validatorPlugin->getApplicablePaymentProvider();
            if (!$this->isValidatorApplicable($refundedPaymentProviders, $applicablePaymentProvider)) {
                continue;
            }

            $refundTransfer = $this->createRefundTransfer(
                $idSalesOrder,
                $itemRefunds,
                $expenseRefunds,
                $applicablePaymentProvider
            );

            $refundTransfer = $validatorPlugin->validate($refundTransfer);
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
     * @param array $usedPaymentProviders
     * @param string $validatorPaymentProvider
     *
     * @return bool
     */
    private function isValidatorApplicable(array $usedPaymentProviders, string $validatorPaymentProvider): bool
    {
        return in_array($validatorPaymentProvider, $usedPaymentProviders);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemRefundTransfer[][] $itemRefunds
     * @param \Generated\Shared\Transfer\ExpenseRefundTransfer[][] $expenseRefunds
     *
     * @return string[]
     */
    private function getUsedPaymentProviders(array $itemRefunds, array $expenseRefunds): array
    {
        return array_unique(array_merge(array_keys($itemRefunds), array_keys($expenseRefunds)));
    }

    /**
     * @param int $idSalesOrder
     * @param \Generated\Shared\Transfer\ItemRefundTransfer[][] $itemRefunds
     * @param \Generated\Shared\Transfer\ExpenseRefundTransfer[][] $expenseRefunds
     * @param string $applicablePaymentProvider
     *
     * @return \Generated\Shared\Transfer\RefundTransfer
     */
    private function createRefundTransfer(
        int $idSalesOrder,
        array $itemRefunds,
        array $expenseRefunds,
        string $applicablePaymentProvider
    ): RefundTransfer {
        $filteredItemRefunds = $itemRefunds[$applicablePaymentProvider] ?? [];
        $filteredExpenseRefunds = $expenseRefunds[$applicablePaymentProvider] ?? [];

        return (new RefundTransfer())
            ->setFkSalesOrder($idSalesOrder)
            ->setItemRefunds(new ArrayObject($filteredItemRefunds))
            ->setExpenseRefunds(new ArrayObject($filteredExpenseRefunds));
    }

    /**
     * @param iterable|\Generated\Shared\Transfer\PaymentTransfer[] $payments
     *
     * @return string[]
     */
    private function getPaymentMethodIdToProviderMap(iterable $payments): array
    {
        $paymentMap = [];
        foreach ($payments as $paymentTransfer) {
            $paymentMap[$paymentTransfer->getIdSalesPayment()] = $paymentTransfer->getPaymentProvider();
        }

        return $paymentMap;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     * @param array $paymentMap
     *
     * @return \Generated\Shared\Transfer\ItemRefundTransfer[][]
     */
    private function getItemPendingRefunds(array $itemTransfers, array $paymentMap): array
    {
        $itemRefunds = $this->collectItemRefundTransfers($itemTransfers);
        $pendingItemRefunds = $this->filterPendingRefunds($itemRefunds);

        return $this->mapRefundsByPaymentProvider($pendingItemRefunds, $paymentMap);
    }

    /**
     * @param \Generated\Shared\Transfer\ExpenseTransfer[] $expenseTransfers
     * @param array $paymentMap
     *
     * @return \Generated\Shared\Transfer\ExpenseRefundTransfer[][]
     */
    private function getExpensePendingRefunds(array $expenseTransfers, array $paymentMap): array
    {
        $expenseRefunds = $this->collectExpenseRefundTransfers($expenseTransfers);
        $pendingExpenseRefunds = $this->filterPendingRefunds($expenseRefunds);

        return $this->mapRefundsByPaymentProvider($pendingExpenseRefunds, $paymentMap);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemRefundTransfer[]|\Generated\Shared\Transfer\ExpenseRefundTransfer[] $refundTransfers
     * @param array $paymentMap
     *
     * @throws \Pyz\Zed\Refund\Business\Exception\RefundValidatingException
     *
     * @return \Generated\Shared\Transfer\ItemRefundTransfer[][]|\Generated\Shared\Transfer\ExpenseRefundTransfer[][]
     */
    private function mapRefundsByPaymentProvider(array $refundTransfers, array $paymentMap): array
    {
        $mappedRefunds = [];
        foreach ($refundTransfers as $refundTransfer) {
            $paymentProvider = $paymentMap[$refundTransfer->getFkSalesPayment()] ?? null;
            if (!$paymentProvider) {
                throw new RefundValidatingException(self::EXCEPTION_NOT_FOUND_PAYMENT_METHOD_NAME);
            }

            $mappedRefunds[$paymentProvider][] = $refundTransfer;
        }

        return $mappedRefunds;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemRefundTransfer[]|\Generated\Shared\Transfer\ExpenseRefundTransfer[] $refundTransfers
     *
     * @return \Generated\Shared\Transfer\ItemRefundTransfer[]|\Generated\Shared\Transfer\ExpenseRefundTransfer[]
     */
    private function filterPendingRefunds(array $refundTransfers): array
    {
        return array_filter(
            $refundTransfers,
            function (AbstractTransfer $refundTransfer): bool {
                /**
                 * @var \Generated\Shared\Transfer\ItemRefundTransfer|\Generated\Shared\Transfer\ExpenseRefundTransfer $refundTransfer
                 */
                return $refundTransfer->getStatus() === RefundConfig::PAYMENT_REFUND_STATUS_PENDING;
            }
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return \Generated\Shared\Transfer\ItemRefundTransfer[]
     */
    private function collectItemRefundTransfers(array $itemTransfers): array
    {
        return array_reduce(
            $itemTransfers,
            function (array $itemRefundTransfers, ItemTransfer $itemTransfer): array {
                return array_merge($itemRefundTransfers, $itemTransfer->getRefunds()->getArrayCopy());
            },
            []
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ExpenseTransfer[] $expenseTransfers
     *
     * @return \Generated\Shared\Transfer\ExpenseRefundTransfer[]
     */
    private function collectExpenseRefundTransfers(array $expenseTransfers): array
    {
        return array_reduce(
            $expenseTransfers,
            function (array $expenseRefundTransfers, ExpenseTransfer $expenseTransfer): array {
                return array_merge($expenseRefundTransfers, $expenseTransfer->getRefunds()->getArrayCopy());
            },
            []
        );
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem[] $orderItems
     *
     * @return int[]
     */
    private function collectItemIds(array $orderItems): array
    {
        return array_map(
            static function (SpySalesOrderItem $spySalesOrderItem): int {
                return $spySalesOrderItem->getIdSalesOrderItem();
            },
            $orderItems
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     * @param int[] $relevantItemIds
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    private function filterRelevantItems(array $itemTransfers, array $relevantItemIds): array
    {
        return array_filter(
            $itemTransfers,
            function (ItemTransfer $itemTransfer) use ($relevantItemIds): bool {
                return in_array($itemTransfer->getIdSalesOrderItem(), $relevantItemIds);
            }
        );
    }
}
