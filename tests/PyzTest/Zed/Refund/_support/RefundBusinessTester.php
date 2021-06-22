<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Zed\Refund;

use Codeception\Actor;
use Generated\Shared\DataBuilder\ExpenseRefundBuilder;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\DataBuilder\ItemRefundBuilder;
use Generated\Shared\Transfer\ExpenseRefundTransfer;
use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\ItemRefundTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Orm\Zed\Payment\Persistence\SpySalesPayment;
use Orm\Zed\Sales\Persistence\SpySalesExpense;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Pyz\Shared\Adyen\AdyenConfig;
use Pyz\Shared\MyWorldPayment\MyWorldPaymentConfig as SharedMyWorldPaymentConfig;
use Pyz\Shared\Shipment\ShipmentConfig;
use Pyz\Zed\MyWorldPayment\MyWorldPaymentConfig;
use Pyz\Zed\Refund\Business\RefundFacadeInterface;

/**
 * Inherited Methods
 *
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 *
 * @SuppressWarnings(PHPMD)
 */
class RefundBusinessTester extends Actor
{
    use _generated\RefundBusinessTesterActions;

    /**
     * @param array $itemsData
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrder
     */
    public function createSalesOrderWithItems(array $itemsData): SpySalesOrder
    {
        $itemTransfers = array_map(
            function (array $itemOverrideData): ItemTransfer {
                return $this->buildItemTransfer($itemOverrideData);
            },
            $itemsData
        );

        return $this->haveSalesOrderEntity($itemTransfers);
    }

    /**
     * @param array $overrideData
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    public function buildItemTransfer(array $overrideData = []): ItemTransfer
    {
        return (new ItemBuilder($overrideData))->build();
    }

    /**
     * @return \Pyz\Zed\Refund\Business\RefundFacadeInterface
     */
    public function getFacade(): RefundFacadeInterface
    {
        return $this->getLocator()->refund()->facade();
    }

    /**
     * @param int $idSalesOrder
     * @param int $amount
     *
     * @return \Orm\Zed\Payment\Persistence\SpySalesPayment
     */
    public function createAdyenPayment(int $idSalesOrder, int $amount): SpySalesPayment
    {
        return $this->haveSalesPaymentEntity($idSalesOrder, [
            PaymentTransfer::AMOUNT => $amount,
            PaymentTransfer::PAYMENT_PROVIDER => AdyenConfig::PROVIDER_NAME,
            PaymentTransfer::PAYMENT_METHOD => AdyenConfig::ADYEN_CREDIT_CARD,
        ]);
    }

    /**
     * @param int $idSalesOrder
     * @param int $amount
     *
     * @return \Orm\Zed\Payment\Persistence\SpySalesPayment
     */
    public function createCashbackPayment(int $idSalesOrder, int $amount): SpySalesPayment
    {
        return $this->haveSalesPaymentEntity($idSalesOrder, [
            PaymentTransfer::AMOUNT => $amount,
            PaymentTransfer::PAYMENT_PROVIDER => SharedMyWorldPaymentConfig::PAYMENT_PROVIDER_NAME_MY_WORLD,
            PaymentTransfer::PAYMENT_METHOD => MyWorldPaymentConfig::PAYMENT_METHOD_CASHBACK_NAME,
        ]);
    }

    /**
     * @param int $idSalesOrder
     * @param int $amount
     *
     * @return \Orm\Zed\Payment\Persistence\SpySalesPayment
     */
    public function createEVoucherPayment(int $idSalesOrder, int $amount): SpySalesPayment
    {
        return $this->haveSalesPaymentEntity($idSalesOrder, [
            PaymentTransfer::AMOUNT => $amount,
            PaymentTransfer::PAYMENT_PROVIDER => SharedMyWorldPaymentConfig::PAYMENT_PROVIDER_NAME_MY_WORLD,
            PaymentTransfer::PAYMENT_METHOD => MyWorldPaymentConfig::PAYMENT_METHOD_EVOUCHER_NAME,
        ]);
    }

    /**
     * @param int $idSalesOrder
     * @param int $amount
     *
     * @return \Orm\Zed\Payment\Persistence\SpySalesPayment
     */
    public function createMarketerEVoucherPayment(int $idSalesOrder, int $amount): SpySalesPayment
    {
        return $this->haveSalesPaymentEntity($idSalesOrder, [
            PaymentTransfer::AMOUNT => $amount,
            PaymentTransfer::PAYMENT_PROVIDER => SharedMyWorldPaymentConfig::PAYMENT_PROVIDER_NAME_MY_WORLD,
            PaymentTransfer::PAYMENT_METHOD => MyWorldPaymentConfig::PAYMENT_METHOD_EVOUCHER_ON_BEHALF_OF_MARKETER_NAME,
        ]);
    }

    /**
     * @param int $idSalesOrder
     * @param int $amount
     *
     * @return \Orm\Zed\Payment\Persistence\SpySalesPayment
     */
    public function createBenefitVoucherPayment(int $idSalesOrder, int $amount): SpySalesPayment
    {
        return $this->haveSalesPaymentEntity($idSalesOrder, [
            PaymentTransfer::AMOUNT => $amount,
            PaymentTransfer::PAYMENT_PROVIDER => SharedMyWorldPaymentConfig::PAYMENT_PROVIDER_NAME_MY_WORLD,
            PaymentTransfer::PAYMENT_METHOD => MyWorldPaymentConfig::PAYMENT_METHOD_BENEFIT_VOUCHER_NAME,
        ]);
    }

    /**
     * @param int $idSalesOrder
     * @param int $price
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesExpense
     */
    public function createExpense(int $idSalesOrder, int $price): SpySalesExpense
    {
        return $this->haveSalesExpenseEntity(
            $idSalesOrder,
            [
                ExpenseTransfer::UNIT_PRICE => $price,
                ExpenseTransfer::UNIT_GROSS_PRICE => $price,
                ExpenseTransfer::REFUNDABLE_AMOUNT => $price,
                ExpenseTransfer::TYPE => ShipmentConfig::SHIPMENT_EXPENSE_TYPE,
                ExpenseTransfer::NAME => 'Standard Shipping',
            ]
        );
    }

    /**
     * @param array $override
     *
     * @return \Generated\Shared\Transfer\ItemRefundTransfer
     */
    public function buildItemRefundTransfer(array $override): ItemRefundTransfer
    {
        return (new ItemRefundBuilder($override))->build();
    }

    /**
     * @param array $override
     *
     * @return \Generated\Shared\Transfer\ExpenseRefundTransfer
     */
    public function buildExpenseRefundTransfer(array $override): ExpenseRefundTransfer
    {
        return (new ExpenseRefundBuilder($override))->build();
    }
}
