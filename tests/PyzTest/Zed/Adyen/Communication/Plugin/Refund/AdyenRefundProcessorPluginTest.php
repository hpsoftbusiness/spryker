<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Zed\Adyen\Communication\Plugin\Refund;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ExpenseRefundTransfer;
use Generated\Shared\Transfer\ItemRefundTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\PaymentAdyenOrderItemTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\RefundTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Pyz\Shared\Adyen\AdyenConfig;
use Pyz\Shared\MyWorldPayment\MyWorldPaymentConfig;
use Pyz\Zed\Adyen\AdyenDependencyProvider;
use Pyz\Zed\Adyen\Business\AdyenFacade;
use Pyz\Zed\Adyen\Business\AdyenFacadeInterface;
use Pyz\Zed\Adyen\Communication\Plugin\Refund\AdyenRefundProcessorPlugin;
use Pyz\Zed\Refund\RefundConfig;
use SprykerEco\Zed\Adyen\Dependency\Facade\AdyenToSalesFacadeInterface;
use SprykerEco\Zed\Adyen\Persistence\AdyenRepository;
use SprykerEco\Zed\Adyen\Persistence\AdyenRepositoryInterface;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Zed
 * @group Adyen
 * @group Communication
 * @group Plugin
 * @group Refund
 * @group AdyenRefundProcessorPluginTest
 * Add your own group annotations below this line
 */
class AdyenRefundProcessorPluginTest extends Unit
{
    /**
     * @var \PyzTest\Zed\Adyen\AdyenCommunicationTester
     */
    protected $tester;

    /**
     * @var \Pyz\Zed\Adyen\Communication\Plugin\Refund\AdyenRefundProcessorPlugin
     */
    private $sut;

    /**
     * @var \Pyz\Zed\Adyen\Business\AdyenFacadeInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private $adyenFacadeMock;

    /**
     * @var \SprykerEco\Zed\Adyen\Persistence\AdyenRepositoryInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private $adyenRepositoryMock;

    /**
     * @return void
     */
    protected function _before(): void
    {
        $this->adyenFacadeMock = $this->mockAdyenFacade();
        $this->adyenRepositoryMock = $this->mockAdyenRepository();
        $this->sut = $this->createAdyenRefundProcessorPlugin();
        $this->sut->setFacade($this->adyenFacadeMock);
        $this->sut->setRepository($this->adyenRepositoryMock);

        $this->tester->setDependency(
            AdyenDependencyProvider::FACADE_SALES,
            $this->mockSalesFacade()
        );
    }

    /**
     * @return void
     */
    public function testAdyenRefundProcessed(): void
    {
        $refunds = [
            $this->tester->buildRefundTransfer([
                RefundTransfer::PAYMENT => [
                    PaymentTransfer::PAYMENT_PROVIDER => MyWorldPaymentConfig::PAYMENT_PROVIDER_NAME_MY_WORLD,
                ],
            ]),
            $this->tester->buildRefundTransfer([
                RefundTransfer::FK_SALES_ORDER => 5,
                RefundTransfer::PAYMENT => [
                    PaymentTransfer::PAYMENT_PROVIDER => AdyenConfig::PROVIDER_NAME,
                ],
                RefundTransfer::ITEMS => [
                    [
                        ItemTransfer::ID_SALES_ORDER_ITEM => 1,
                    ],
                    [
                        ItemTransfer::ID_SALES_ORDER_ITEM => 2,
                    ],
                ],
                RefundTransfer::ITEM_REFUNDS => [
                    [
                        ItemRefundTransfer::STATUS => RefundConfig::PAYMENT_REFUND_STATUS_NEW,
                    ],
                    [
                        ItemRefundTransfer::STATUS => RefundConfig::PAYMENT_REFUND_STATUS_NEW,
                    ],
                ],
                RefundTransfer::EXPENSE_REFUNDS => [
                    [
                        ExpenseRefundTransfer::STATUS => RefundConfig::PAYMENT_REFUND_STATUS_NEW,
                    ],
                ],
            ]),
        ];

        $this->adyenFacadeMock
            ->expects(self::once())
            ->method('executeRefundCommand')
            ->willReturnCallback(
                function (array $orderItems, OrderTransfer $orderTransfer, RefundTransfer $refundTransfer) {
                    self::assertCount(2, $orderItems);
                    self::assertInstanceOf(SpySalesOrderItem::class, $orderItems[0]);
                    self::assertEquals(1, $orderItems[0]->getIdSalesOrderItem());
                    self::assertInstanceOf(SpySalesOrderItem::class, $orderItems[1]);
                    self::assertEquals(2, $orderItems[1]->getIdSalesOrderItem());
                }
            );

        $this->adyenRepositoryMock
            ->expects(self::once())
            ->method('getOrderItemsByIdsSalesOrderItems')
            ->with([1, 2])
            ->willReturnCallback(
                function () {
                    $statusPending = $this->tester->getConfig()->getOmsStatusRefundPending();

                    return [
                        (new PaymentAdyenOrderItemTransfer())->setStatus($statusPending),
                        (new PaymentAdyenOrderItemTransfer())->setStatus($statusPending),
                    ];
                }
            );

        $refundResponseTransfer = $this->sut->processRefunds($refunds);

        self::assertTrue($refundResponseTransfer->getIsSuccess());
        self::assertCount(1, $refundResponseTransfer->getRefunds());
        foreach ($refundResponseTransfer->getRefunds()[0]->getItemRefunds() as $itemRefund) {
            self::assertEquals(RefundConfig::PAYMENT_REFUND_STATUS_PENDING, $itemRefund->getStatus());
        }

        foreach ($refundResponseTransfer->getRefunds()[0]->getExpenseRefunds() as $expenseRefund) {
            self::assertEquals(RefundConfig::PAYMENT_REFUND_STATUS_PENDING, $expenseRefund->getStatus());
        }
    }

    /**
     * @return void
     */
    public function testAdyenRefundProcessUnsuccessfulResponse(): void
    {
        $refund = $this->tester->buildRefundTransfer([
                RefundTransfer::FK_SALES_ORDER => 5,
                RefundTransfer::PAYMENT => [
                    PaymentTransfer::PAYMENT_PROVIDER => AdyenConfig::PROVIDER_NAME,
                ],
                RefundTransfer::ITEMS => [
                    [
                        ItemTransfer::ID_SALES_ORDER_ITEM => 1,
                    ],
                ],
                RefundTransfer::ITEM_REFUNDS => [
                    [
                        ItemRefundTransfer::STATUS => RefundConfig::PAYMENT_REFUND_STATUS_NEW,
                    ],
                ],
                RefundTransfer::EXPENSE_REFUNDS => [
                    [
                        ExpenseRefundTransfer::STATUS => RefundConfig::PAYMENT_REFUND_STATUS_NEW,
                    ],
                ],
            ]);

        $this->adyenFacadeMock
            ->expects(self::once())
            ->method('executeRefundCommand');

        $this->adyenRepositoryMock
            ->expects(self::once())
            ->method('getOrderItemsByIdsSalesOrderItems')
            ->willReturnCallback(
                function () {
                    $statusPending = $this->tester->getConfig()->getOmsStatusRefundFailed();

                    return [
                        (new PaymentAdyenOrderItemTransfer())->setStatus($statusPending),
                    ];
                }
            );

        $refundResponseTransfer = $this->sut->processRefunds([$refund]);

        self::assertFalse($refundResponseTransfer->getIsSuccess());
        self::assertCount(1, $refundResponseTransfer->getRefunds());
        foreach ($refundResponseTransfer->getRefunds()[0]->getItemRefunds() as $itemRefund) {
            self::assertEquals(RefundConfig::PAYMENT_REFUND_STATUS_FAILED, $itemRefund->getStatus());
        }

        foreach ($refundResponseTransfer->getRefunds()[0]->getExpenseRefunds() as $expenseRefund) {
            self::assertEquals(RefundConfig::PAYMENT_REFUND_STATUS_FAILED, $expenseRefund->getStatus());
        }
    }

    /**
     * @return void
     */
    public function testProcessRefundsReturnsNullIfAdyenRefundMissing(): void
    {
        $refund = $this->tester->buildRefundTransfer([
                RefundTransfer::PAYMENT => [
                    PaymentTransfer::PAYMENT_PROVIDER => MyWorldPaymentConfig::PAYMENT_PROVIDER_NAME_MY_WORLD,
                ],
            ]);

        $this->adyenFacadeMock
            ->expects(self::never())
            ->method('executeRefundCommand');

        $refundResponseTransfer = $this->sut->processRefunds([$refund]);

        self::assertNull($refundResponseTransfer);
    }

    /**
     * @return \Pyz\Zed\Adyen\Communication\Plugin\Refund\AdyenRefundProcessorPlugin
     */
    private function createAdyenRefundProcessorPlugin(): AdyenRefundProcessorPlugin
    {
        return new AdyenRefundProcessorPlugin();
    }

    /**
     * @return \Pyz\Zed\Adyen\Business\AdyenFacadeInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private function mockAdyenFacade(): AdyenFacadeInterface
    {
        return $this->createMock(AdyenFacade::class);
    }

    /**
     * @return \SprykerEco\Zed\Adyen\Persistence\AdyenRepositoryInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private function mockAdyenRepository(): AdyenRepositoryInterface
    {
        return $this->createMock(AdyenRepository::class);
    }

    /**
     * @return \SprykerEco\Zed\Adyen\Dependency\Facade\AdyenToSalesFacadeInterface
     */
    private function mockSalesFacade(): AdyenToSalesFacadeInterface
    {
        $salesFacadeMock = $this->createMock(AdyenToSalesFacadeInterface::class);
        $salesFacadeMock
            ->method('getOrderByIdSalesOrder')
            ->willReturn(new OrderTransfer());

        return $salesFacadeMock;
    }
}
