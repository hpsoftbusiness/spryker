<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Shared\Sales\Helper;

use Generated\Shared\DataBuilder\ExpenseBuilder;
use Generated\Shared\DataBuilder\PaymentBuilder;
use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Orm\Zed\BenefitDeal\Persistence\PyzSalesOrderItemBenefitDeal;
use Orm\Zed\Oms\Persistence\SpyOmsOrderItemState;
use Orm\Zed\Oms\Persistence\SpyOmsOrderProcess;
use Orm\Zed\Payment\Persistence\SpySalesPayment;
use Orm\Zed\Payment\Persistence\SpySalesPaymentMethodType;
use Orm\Zed\Payment\Persistence\SpySalesPaymentMethodTypeQuery;
use Orm\Zed\Payment\Persistence\SpySalesPaymentQuery;
use Orm\Zed\Sales\Persistence\SpySalesExpense;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Pyz\Zed\MyWorldPayment\MyWorldPaymentConfig;
use SprykerTest\Zed\Sales\Helper\BusinessHelper;

class SalesOrderDataHelper extends BusinessHelper
{
    public const DEFAULT_OMS_PROCESS_NAME = 'DummyPrepayment01';
    public const DEFAULT_ITEM_STATE = 'new';

    /**
     * @param int $idSalesOrder
     * @param array $paymentOverride
     *
     * @return \Orm\Zed\Payment\Persistence\SpySalesPayment
     */
    public function haveSalesPaymentEntity(int $idSalesOrder, array $paymentOverride): SpySalesPayment
    {
        $paymentTransfer = $this->buildPaymentTransfer($paymentOverride);

        $salesPaymentMethodTypeEntity = $this->haveSalesPaymentMethodTypeEntity(
            $paymentTransfer->getPaymentMethod(),
            $paymentTransfer->getPaymentProvider()
        );

        $spySalesPayment = $this->createSpySalesPaymentQuery()
            ->filterByFkSalesPaymentMethodType($salesPaymentMethodTypeEntity->getIdSalesPaymentMethodType())
            ->filterByFkSalesOrder($idSalesOrder)
            ->findOneOrCreate();

        $spySalesPayment->setAmount($paymentTransfer->getAmount());
        $spySalesPayment->setAvailableAmount($paymentTransfer->getAvailableAmount());
        $spySalesPayment->setIsLimitedAmount($paymentTransfer->getIsLimitedAmount());
        $spySalesPayment->save();

        return $spySalesPayment;
    }

    /**
     * @param int $idSalesOrder
     * @param array $expenseOverride
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesExpense
     */
    public function haveSalesExpenseEntity(int $idSalesOrder, array $expenseOverride): SpySalesExpense
    {
        $expenseTransfer = $this->buildExpenseTransfer($expenseOverride);
        $salesExpenseEntity = new SpySalesExpense();
        $salesExpenseEntity->fromArray($expenseTransfer->toArray());
        $salesExpenseEntity->setGrossPrice($expenseTransfer->getUnitGrossPrice());
        $salesExpenseEntity->setFkSalesOrder($idSalesOrder);
        $salesExpenseEntity->save();

        return $salesExpenseEntity;
    }

    /**
     * @param \Orm\Zed\Oms\Persistence\SpyOmsOrderItemState $omsStateEntity
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $salesOrderEntity
     * @param \Orm\Zed\Oms\Persistence\SpyOmsOrderProcess $omsOrderProcessEntity
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param int $quantity
     * @param int $grossPrice
     * @param int $taxRate
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItem
     */
    protected function createOrderItem(
        SpyOmsOrderItemState $omsStateEntity,
        SpySalesOrder $salesOrderEntity,
        SpyOmsOrderProcess $omsOrderProcessEntity,
        ItemTransfer $itemTransfer,
        int $quantity,
        int $grossPrice,
        int $taxRate
    ): SpySalesOrderItem {
        $salesOrderItem = new SpySalesOrderItem();
        $salesOrderItem->fromArray($itemTransfer->toArray());
        $salesOrderItem->setGrossPrice($grossPrice);
        $salesOrderItem->setQuantity($quantity);
        $salesOrderItem->setSku($itemTransfer->getSku());
        $salesOrderItem->setName($itemTransfer->getName());
        $salesOrderItem->setTaxRate($taxRate);
        $salesOrderItem->setFkOmsOrderItemState($omsStateEntity->getIdOmsOrderItemState());
        $salesOrderItem->setProcess($omsOrderProcessEntity);
        $salesOrderItem->setFkSalesOrder($salesOrderEntity->getIdSalesOrder());
        $salesOrderItem->setGroupKey($itemTransfer->getGroupKey());
        $salesOrderItem->save();

        if ($itemTransfer->getUseBenefitVoucher()) {
            $this->saveBenefitVoucherOrderItemBenefitDeal($salesOrderItem->getIdSalesOrderItem(), $itemTransfer);
        }

        if ($itemTransfer->getUseShoppingPoints()) {
            $this->saveShoppingPointsOrderItemBenefitDeal($salesOrderItem->getIdSalesOrderItem(), $itemTransfer);
        }

        $itemTransfer->setIdSalesOrderItem($salesOrderItem->getIdSalesOrderItem());

        return $salesOrderItem;
    }

    /**
     * @param int $idSalesOrderItem
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return void
     */
    private function saveBenefitVoucherOrderItemBenefitDeal(int $idSalesOrderItem, ItemTransfer $itemTransfer): void
    {
        $orderItemBenefitDealEntity = new PyzSalesOrderItemBenefitDeal();
        $orderItemBenefitDealEntity->setType(MyWorldPaymentConfig::PAYMENT_METHOD_BENEFIT_VOUCHER_NAME);
        $orderItemBenefitDealEntity->setUnitBenefitPrice($itemTransfer->getUnitBenefitPrice());
        $orderItemBenefitDealEntity->setBenefitVoucherAmount($itemTransfer->getTotalUsedBenefitVouchersAmount());
        $orderItemBenefitDealEntity->setFkSalesOrderItem($idSalesOrderItem);
        $orderItemBenefitDealEntity->save();
    }

    /**
     * @param int $idSalesOrderItem
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return void
     */
    private function saveShoppingPointsOrderItemBenefitDeal(int $idSalesOrderItem, ItemTransfer $itemTransfer): void
    {
        $orderItemBenefitDealEntity = new PyzSalesOrderItemBenefitDeal();
        $orderItemBenefitDealEntity->setType(MyWorldPaymentConfig::PAYMENT_METHOD_SHOPPING_POINTS);
        $orderItemBenefitDealEntity->setUnitBenefitPrice($itemTransfer->getUnitBenefitPrice());
        $orderItemBenefitDealEntity->setShoppingPointsAmount($itemTransfer->getTotalUsedShoppingPointsAmount());
        $orderItemBenefitDealEntity->setFkSalesOrderItem($idSalesOrderItem);
        $orderItemBenefitDealEntity->save();
    }

    /**
     * @param array $override
     *
     * @return \Generated\Shared\Transfer\ExpenseTransfer
     */
    private function buildExpenseTransfer(array $override): ExpenseTransfer
    {
        return (new ExpenseBuilder($override))->build();
    }

    /**
     * @param string $method
     * @param string $provider
     *
     * @return \Orm\Zed\Payment\Persistence\SpySalesPaymentMethodType
     */
    private function haveSalesPaymentMethodTypeEntity(string $method, string $provider): SpySalesPaymentMethodType
    {
        $entity = $this->createSpySalesPaymentMethodTypeQuery()
            ->filterByPaymentMethod($method)
            ->filterByPaymentProvider($provider)
            ->findOneOrCreate();

        $entity->save();

        return $entity;
    }

    /**
     * @param array $override
     *
     * @return \Generated\Shared\Transfer\PaymentTransfer
     */
    private function buildPaymentTransfer(array $override): PaymentTransfer
    {
        return (new PaymentBuilder($override))->build();
    }

    /**
     * @return \Orm\Zed\Payment\Persistence\SpySalesPaymentMethodTypeQuery
     */
    private function createSpySalesPaymentMethodTypeQuery(): SpySalesPaymentMethodTypeQuery
    {
        return SpySalesPaymentMethodTypeQuery::create();
    }

    /**
     * @return \Orm\Zed\Payment\Persistence\SpySalesPaymentQuery
     */
    private function createSpySalesPaymentQuery(): SpySalesPaymentQuery
    {
        return SpySalesPaymentQuery::create();
    }
}
