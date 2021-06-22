<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Zed\Refund\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ExpenseRefundTransfer;
use Generated\Shared\Transfer\ItemRefundTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\RefundTransfer;
use Pyz\Shared\Adyen\AdyenConfig;
use Pyz\Zed\MyWorldPayment\MyWorldPaymentConfig;
use Pyz\Zed\Refund\Dependency\Plugin\RefundProcessorPluginInterface;
use Pyz\Zed\Refund\RefundConfig;
use Pyz\Zed\Refund\RefundDependencyProvider;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Zed
 * @group Refund
 * @group Business
 * @group Facade
 * @group ProcessRefundsFacadeTest
 * Add your own group annotations below this line
 */
class ProcessRefundsFacadeTest extends Unit
{
    private const ITEM_1_SEED_DATA = [
        ItemTransfer::QUANTITY => 1,
        ItemTransfer::UNIT_PRICE => 1000,
        ItemTransfer::SUM_GROSS_PRICE => 1000,
        ItemTransfer::UNIT_GROSS_PRICE => 1000,
        ItemTransfer::REFUNDABLE_AMOUNT => 1000,
    ];

    private const ITEM_2_SEED_DATA = [
        ItemTransfer::QUANTITY => 1,
        ItemTransfer::UNIT_PRICE => 400,
        ItemTransfer::SUM_GROSS_PRICE => 400,
        ItemTransfer::UNIT_GROSS_PRICE => 400,
        ItemTransfer::REFUNDABLE_AMOUNT => 400,
    ];

    /**
     * @var \PyzTest\Zed\Refund\RefundBusinessTester
     */
    protected $tester;

    /**
     * @var \Pyz\Zed\Refund\Business\RefundFacadeInterface
     */
    private $sut;

    /**
     * @var \Pyz\Zed\Refund\Dependency\Plugin\RefundProcessorPluginInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private $refundProcessorPluginMock;

    /**
     * @return void
     */
    protected function _before(): void
    {
        $this->sut = $this->tester->getFacade();
        $this->refundProcessorPluginMock = $this->mockRefundProcessorPlugin();
        $this->tester->setDependency(
            RefundDependencyProvider::PLUGIN_REFUND_PROCESSOR,
            function () {
                return [
                    $this->refundProcessorPluginMock,
                ];
            }
        );
    }

    /**
     * @return void
     */
    public function testRefundProcessor(): void
    {
        $salesOrder = $this->tester->createSalesOrderWithItems([self::ITEM_1_SEED_DATA, self::ITEM_2_SEED_DATA]);
        $salesExpense = $this->tester->createExpense($salesOrder->getIdSalesOrder(), 450);
        $adyenPayment = $this->tester->createAdyenPayment($salesOrder->getIdSalesOrder(), 1500);
        $cashbackPayment = $this->tester->createCashbackPayment($salesOrder->getIdSalesOrder(), 350);

        /**
         * @var \Orm\Zed\Sales\Persistence\SpySalesOrderItem[] $orderItems
         */
        $orderItems = $salesOrder->getItems()->getData();
        $itemAdyenRefund_1 = $this->tester->buildItemRefundTransfer([
            ItemRefundTransfer::AMOUNT => 1000,
            ItemRefundTransfer::STATUS => RefundConfig::PAYMENT_REFUND_STATUS_NEW,
            ItemRefundTransfer::FK_SALES_ORDER_ITEM => $orderItems[0]->getIdSalesOrderItem(),
            ItemRefundTransfer::FK_SALES_PAYMENT => $adyenPayment->getIdSalesPayment(),
        ]);
        $this->tester->haveOrderItemRefundEntity($itemAdyenRefund_1);
        $itemAdyenRefund_2 = $this->tester->buildItemRefundTransfer([
            ItemRefundTransfer::AMOUNT => 400,
            ItemRefundTransfer::STATUS => RefundConfig::PAYMENT_REFUND_STATUS_NEW,
            ItemRefundTransfer::FK_SALES_ORDER_ITEM => $orderItems[1]->getIdSalesOrderItem(),
            ItemRefundTransfer::FK_SALES_PAYMENT => $adyenPayment->getIdSalesPayment(),
        ]);
        $this->tester->haveOrderItemRefundEntity($itemAdyenRefund_2);
        $expenseAdyenRefund = $this->tester->buildExpenseRefundTransfer([
            ExpenseRefundTransfer::AMOUNT => 100,
            ExpenseRefundTransfer::STATUS => RefundConfig::PAYMENT_REFUND_STATUS_NEW,
            ExpenseRefundTransfer::FK_SALES_EXPENSE => $salesExpense->getIdSalesExpense(),
            ExpenseRefundTransfer::FK_SALES_PAYMENT => $adyenPayment->getIdSalesPayment(),
        ]);
        $this->tester->haveSalesExpenseRefundEntity($expenseAdyenRefund);
        $expenseCashbackRefund = $this->tester->buildExpenseRefundTransfer([
            ExpenseRefundTransfer::AMOUNT => 350,
            ExpenseRefundTransfer::STATUS => RefundConfig::PAYMENT_REFUND_STATUS_NEW,
            ExpenseRefundTransfer::FK_SALES_EXPENSE => $salesExpense->getIdSalesExpense(),
            ExpenseRefundTransfer::FK_SALES_PAYMENT => $cashbackPayment->getIdSalesPayment(),
        ]);
        $this->tester->haveSalesExpenseRefundEntity($expenseCashbackRefund);

        $this->refundProcessorPluginMock
            ->expects(self::once())
            ->method('processRefunds')
            ->willReturnCallback(function (array $refundTransfers) {
                self::assertCount(2, $refundTransfers);
                $adyenRefundTransfer = $this->findRefundInCollectionByPaymentName(
                    AdyenConfig::ADYEN_CREDIT_CARD,
                    $refundTransfers
                );
                self::assertNotNull($adyenRefundTransfer);
                self::assertEquals(1500, $adyenRefundTransfer->getAmount());
                self::assertCount(2, $adyenRefundTransfer->getItemRefunds());
                self::assertCount(1, $adyenRefundTransfer->getExpenseRefunds());

                $cashbackRefundTransfer = $this->findRefundInCollectionByPaymentName(
                    MyWorldPaymentConfig::PAYMENT_METHOD_CASHBACK_NAME,
                    $refundTransfers
                );
                self::assertNotNull($cashbackRefundTransfer);
                self::assertEquals(350, $cashbackRefundTransfer->getAmount());
                self::assertCount(0, $cashbackRefundTransfer->getItemRefunds());
                self::assertCount(1, $cashbackRefundTransfer->getExpenseRefunds());
            });

        $this->sut->processRefund($orderItems, $salesOrder);
    }

    /**
     * @return \Pyz\Zed\Refund\Dependency\Plugin\RefundProcessorPluginInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private function mockRefundProcessorPlugin(): RefundProcessorPluginInterface
    {
        return $this->createMock(RefundProcessorPluginInterface::class);
    }

    /**
     * @param string $paymentName
     * @param \Generated\Shared\Transfer\RefundTransfer[] $refundTransfers
     *
     * @return \Generated\Shared\Transfer\RefundTransfer|null
     */
    private function findRefundInCollectionByPaymentName(string $paymentName, array $refundTransfers): ?RefundTransfer
    {
        foreach ($refundTransfers as $refundTransfer) {
            if ($refundTransfer->getPayment()->getPaymentMethod() === $paymentName) {
                return $refundTransfer;
            }
        }

        return null;
    }
}
