<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Zed\Adyen\Communication\Plugin\Refund;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\ExpenseRefundTransfer;
use Generated\Shared\Transfer\ItemRefundTransfer;
use Generated\Shared\Transfer\PaymentAdyenOrderItemTransfer;
use Generated\Shared\Transfer\RefundTransfer;
use Pyz\Zed\Adyen\Communication\Plugin\Refund\AdyenRefundValidatorPlugin;
use Pyz\Zed\Refund\Business\Exception\RefundValidatingException;
use Pyz\Zed\Refund\RefundConfig;
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
 * @group AdyenRefundValidatorPluginTest
 * Add your own group annotations below this line
 */
class AdyenRefundValidatorPluginTest extends Test
{
    private const REFUND_SEED_DATA = [
        RefundTransfer::ITEM_REFUNDS => [
            [
                ItemRefundTransfer::STATUS => RefundConfig::PAYMENT_REFUND_STATUS_PENDING,
                ItemRefundTransfer::FK_SALES_ORDER_ITEM => 1,
            ],
            [
                ItemRefundTransfer::STATUS => RefundConfig::PAYMENT_REFUND_STATUS_PENDING,
                ItemRefundTransfer::FK_SALES_ORDER_ITEM => 2,
            ],
        ],
        RefundTransfer::EXPENSE_REFUNDS => [
            [
                ExpenseRefundTransfer::STATUS => RefundConfig::PAYMENT_REFUND_STATUS_PENDING,
            ],
        ],
    ];

    /**
     * @var \PyzTest\Zed\Adyen\AdyenCommunicationTester
     */
    protected $tester;

    /**
     * @var \Pyz\Zed\Adyen\Communication\Plugin\Refund\AdyenRefundValidatorPlugin
     */
    private $sut;

    /**
     * @var \SprykerEco\Zed\Adyen\Persistence\AdyenRepositoryInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private $adyenRepositoryMock;

    /**
     * @return void
     */
    protected function _before(): void
    {
        $this->sut = $this->createAdyenRefundValidatorPlugin();
        $this->adyenRepositoryMock = $this->mockAdyenRepository();
        $this->sut->setRepository($this->adyenRepositoryMock);
    }

    /**
     * @return void
     */
    public function testValidateSuccessfulRefund(): void
    {
        $refundTransfer = $this->tester->buildRefundTransfer(self::REFUND_SEED_DATA);

        $this->adyenRepositoryMock
            ->expects(self::once())
            ->method('getOrderItemsByIdsSalesOrderItems')
            ->with([1, 2])
            ->willReturn([
                (new PaymentAdyenOrderItemTransfer())
                    ->setStatus($this->tester->getConfig()->getOmsStatusRefunded())
                    ->setFkSalesOrderItem(1),
                (new PaymentAdyenOrderItemTransfer())
                    ->setStatus($this->tester->getConfig()->getOmsStatusRefunded())
                    ->setFkSalesOrderItem(2),
            ]);

        $refundTransfer = $this->sut->validate($refundTransfer);

        foreach ($refundTransfer->getItemRefunds() as $itemRefundTransfer) {
            self::assertEquals(RefundConfig::PAYMENT_REFUND_STATUS_PROCESSED, $itemRefundTransfer->getStatus());
        }

        self::assertEquals(
            RefundConfig::PAYMENT_REFUND_STATUS_PROCESSED,
            $refundTransfer->getExpenseRefunds()[0]->getStatus()
        );
    }

    /**
     * @return void
     */
    public function testValidateFailedRefund(): void
    {
        $refundTransfer = $this->tester->buildRefundTransfer(self::REFUND_SEED_DATA);

        $this->adyenRepositoryMock
            ->expects(self::once())
            ->method('getOrderItemsByIdsSalesOrderItems')
            ->with([1, 2])
            ->willReturn([
                (new PaymentAdyenOrderItemTransfer())
                    ->setStatus($this->tester->getConfig()->getOmsStatusRefundFailed())
                    ->setFkSalesOrderItem(1),
                (new PaymentAdyenOrderItemTransfer())
                    ->setStatus($this->tester->getConfig()->getOmsStatusRefunded())
                    ->setFkSalesOrderItem(2),
            ]);

        $refundTransfer = $this->sut->validate($refundTransfer);

        self::assertEquals(
            RefundConfig::PAYMENT_REFUND_STATUS_FAILED,
            $refundTransfer->getItemRefunds()[0]->getStatus()
        );

        self::assertEquals(
            RefundConfig::PAYMENT_REFUND_STATUS_PROCESSED,
            $refundTransfer->getItemRefunds()[1]->getStatus()
        );

        self::assertEquals(
            RefundConfig::PAYMENT_REFUND_STATUS_FAILED,
            $refundTransfer->getExpenseRefunds()[0]->getStatus()
        );
    }

    /**
     * @return void
     */
    public function testRefundValidateThrowsExceptionIfRelatedPaymentNotFound(): void
    {
        self::expectException(RefundValidatingException::class);

        $refundTransfer = $this->tester->buildRefundTransfer(self::REFUND_SEED_DATA);

        $this->adyenRepositoryMock
            ->expects(self::once())
            ->method('getOrderItemsByIdsSalesOrderItems')
            ->willReturn([
                (new PaymentAdyenOrderItemTransfer())
                    ->setStatus($this->tester->getConfig()->getOmsStatusRefunded())
                    ->setFkSalesOrderItem(2),
            ]);

        $this->sut->validate($refundTransfer);
    }

    /**
     * @return void
     */
    public function testRefundValidateThrowsExceptionIfRefundItemIsNotInRefundProcess(): void
    {
        self::expectException(RefundValidatingException::class);

        $refundTransfer = $this->tester->buildRefundTransfer(self::REFUND_SEED_DATA);

        $this->adyenRepositoryMock
            ->expects(self::once())
            ->method('getOrderItemsByIdsSalesOrderItems')
            ->willReturn([
                (new PaymentAdyenOrderItemTransfer())
                    ->setStatus($this->tester->getConfig()->getOmsStatusAuthorized())
                    ->setFkSalesOrderItem(1),
                (new PaymentAdyenOrderItemTransfer())
                    ->setStatus($this->tester->getConfig()->getOmsStatusRefunded())
                    ->setFkSalesOrderItem(2),
            ]);

        $this->sut->validate($refundTransfer);
    }

    /**
     * @return \Pyz\Zed\Adyen\Communication\Plugin\Refund\AdyenRefundValidatorPlugin
     */
    private function createAdyenRefundValidatorPlugin(): AdyenRefundValidatorPlugin
    {
        return new AdyenRefundValidatorPlugin();
    }

    /**
     * @return \SprykerEco\Zed\Adyen\Persistence\AdyenRepositoryInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private function mockAdyenRepository(): AdyenRepositoryInterface
    {
        return $this->createMock(AdyenRepository::class);
    }
}
