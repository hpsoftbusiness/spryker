<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Zed\Refund\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Pyz\Zed\Refund\RefundConfig;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Zed
 * @group Refund
 * @group Business
 * @group Facade
 * @group CalculateRefundsWithoutExternalPaymentsFacadeTest
 * Add your own group annotations below this line
 */
class CalculateRefundsWithoutExternalPaymentsFacadeTest extends Unit
{
    private const ITEM_1_SEED_DATA = [
        ItemTransfer::QUANTITY => 1,
        ItemTransfer::UNIT_PRICE => 7000,
        ItemTransfer::SUM_GROSS_PRICE => 7000,
        ItemTransfer::UNIT_GROSS_PRICE => 7000,
        ItemTransfer::REFUNDABLE_AMOUNT => 7000,
    ];

    private const ITEM_2_SEED_DATA = [
        ItemTransfer::QUANTITY => 1,
        ItemTransfer::UNIT_PRICE => 9000,
        ItemTransfer::SUM_GROSS_PRICE => 9000,
        ItemTransfer::UNIT_GROSS_PRICE => 9000,
        ItemTransfer::REFUNDABLE_AMOUNT => 9000,
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
     * @return void
     */
    protected function _before(): void
    {
        $this->sut = $this->tester->getFacade();
    }

    /**
     * @test
     *
     * @return void
     */
    public function itShouldRefundCashbackAndBenefitVoucher(): void
    {
        $salesOrder = $this->tester->createSalesOrderWithItems([
            array_merge(self::ITEM_1_SEED_DATA, [
                ItemTransfer::USE_BENEFIT_VOUCHER => true,
                ItemTransfer::TOTAL_USED_BENEFIT_VOUCHERS_AMOUNT => 2500,
            ]),
            array_merge(self::ITEM_2_SEED_DATA, [
                ItemTransfer::USE_BENEFIT_VOUCHER => true,
                ItemTransfer::TOTAL_USED_BENEFIT_VOUCHERS_AMOUNT => 2500,
            ]),
        ]);
        $expenseEntity = $this->tester->createExpense($salesOrder->getIdSalesOrder(), 450);
        $paymentCashback = $this->tester->createCashbackPayment($salesOrder->getIdSalesOrder(), 2000);
        $paymentBenefitVoucher = $this->tester->createBenefitVoucherPayment($salesOrder->getIdSalesOrder(), 5000);
        $paymentAdyen = $this->tester->createAdyenPayment($salesOrder->getIdSalesOrder(), 9450);

        // Act
        $refundTransfer = $this->sut->calculateRefundWithoutExternalPayment($salesOrder->getItems()->getData(), $salesOrder);

        // Assert
        self::assertCount(2, $refundTransfer->getItems());

        $firstItem = $refundTransfer->getItems()[0];
        self::assertCount(1, $firstItem->getRefunds());

        $refundsFirstItem = $firstItem->getRefunds();
        $refundBenefitVoucher = $this->findRefundByIdSalesPaymentInCollection(
            $paymentBenefitVoucher->getIdSalesPayment(),
            $refundsFirstItem
        );
        self::assertEquals(2500, $refundBenefitVoucher->getAmount());

        $secondItem = $refundTransfer->getItems()[1];
        self::assertCount(2, $secondItem->getRefunds());
        $refundsSecondItem = $secondItem->getRefunds();
        $refundBenefitVoucherSecondItem = $this->findRefundByIdSalesPaymentInCollection(
            $paymentBenefitVoucher->getIdSalesPayment(),
            $refundsSecondItem
        );
        self::assertEquals(2500, $refundBenefitVoucherSecondItem->getAmount());

        $refundCashback = $this->findRefundByIdSalesPaymentInCollection(
            $paymentCashback->getIdSalesPayment(),
            $refundsSecondItem
        );
        self::assertEquals(1550, $refundCashback->getAmount());

        $refundCashbackExpense = $this->findExpenseInCollectionById(
            $expenseEntity->getIdSalesExpense(),
            $refundTransfer->getExpenses()
        );
        self::assertNotNull($refundCashbackExpense);
        self::assertCount(1, $refundCashbackExpense->getRefunds());
        $expenseRefundTransfer = $refundCashbackExpense->getRefunds()[0];
        self::assertEquals(450, $expenseRefundTransfer->getAmount());
        self::assertEquals(RefundConfig::PAYMENT_REFUND_STATUS_NEW, $expenseRefundTransfer->getStatus());
        self::assertEquals($paymentCashback->getIdSalesPayment(), $expenseRefundTransfer->getFkSalesPayment());
    }

    /**
     * @param int $idSalesExpense
     * @param iterable|\Generated\Shared\Transfer\ExpenseTransfer[] $expenseTransfers
     *
     * @return \Generated\Shared\Transfer\ExpenseTransfer|null
     */
    private function findExpenseInCollectionById(int $idSalesExpense, iterable $expenseTransfers): ?ExpenseTransfer
    {
        foreach ($expenseTransfers as $expenseTransfer) {
            if ($expenseTransfer->getIdSalesExpense() === $idSalesExpense) {
                return $expenseTransfer;
            }
        }

        return null;
    }

    /**
     * @param int $idSalesPayment
     * @param iterable|\Generated\Shared\Transfer\ItemRefundTransfer[]|\Generated\Shared\Transfer\ExpenseRefundTransfer[] $refunds
     *
     * @return \Generated\Shared\Transfer\ExpenseRefundTransfer|\Generated\Shared\Transfer\ItemRefundTransfer|null
     */
    private function findRefundByIdSalesPaymentInCollection(int $idSalesPayment, iterable $refunds)
    {
        foreach ($refunds as $refundTransfer) {
            if ($refundTransfer->getFkSalesPayment() === $idSalesPayment) {
                return $refundTransfer;
            }
        }

        return null;
    }
}
