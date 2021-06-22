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
 * @group CalculateRefundsFacadeTest
 * Add your own group annotations below this line
 */
class CalculateRefundsFacadeTest extends Unit
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
     * @return void
     */
    protected function _before(): void
    {
        $this->sut = $this->tester->getFacade();
    }

    /**
     * @return void
     */
    public function testRefundCalculationWithAdyenPaymentMethod(): void
    {
        $salesOrder = $this->tester->createSalesOrderWithItems([self::ITEM_1_SEED_DATA, self::ITEM_2_SEED_DATA]);
        $paymentEntity = $this->tester->createAdyenPayment($salesOrder->getIdSalesOrder(), 1850);
        $expenseEntity = $this->tester->createExpense($salesOrder->getIdSalesOrder(), 450);

        $refundTransfer = $this->sut->calculateRefund($salesOrder->getItems()->getData(), $salesOrder);

        self::assertCount(1, $refundTransfer->getItems()[0]->getRefunds());
        $itemRefundTransfer = $refundTransfer->getItems()[0]->getRefunds()[0];
        self::assertEquals(1000, $itemRefundTransfer->getAmount());
        self::assertEquals(RefundConfig::PAYMENT_REFUND_STATUS_NEW, $itemRefundTransfer->getStatus());
        self::assertEquals($paymentEntity->getIdSalesPayment(), $itemRefundTransfer->getFkSalesPayment());
        self::assertEquals($refundTransfer->getItems()[0]->getIdSalesOrderItem(), $itemRefundTransfer->getFkSalesOrderItem());

        self::assertCount(1, $refundTransfer->getItems()[1]->getRefunds());
        $itemRefundTransfer = $refundTransfer->getItems()[1]->getRefunds()[0];
        self::assertEquals(400, $itemRefundTransfer->getAmount());
        self::assertEquals(RefundConfig::PAYMENT_REFUND_STATUS_NEW, $itemRefundTransfer->getStatus());
        self::assertEquals($paymentEntity->getIdSalesPayment(), $itemRefundTransfer->getFkSalesPayment());
        self::assertEquals($refundTransfer->getItems()[1]->getIdSalesOrderItem(), $itemRefundTransfer->getFkSalesOrderItem());

        $expenseTransfer = $this->findExpenseInCollectionById(
            $expenseEntity->getIdSalesExpense(),
            $refundTransfer->getExpenses()
        );
        self::assertNotNull($expenseTransfer);
        self::assertCount(1, $expenseTransfer->getRefunds());
        $expenseRefundTransfer = $expenseTransfer->getRefunds()[0];
        self::assertEquals(450, $expenseRefundTransfer->getAmount());
        self::assertEquals(RefundConfig::PAYMENT_REFUND_STATUS_NEW, $expenseRefundTransfer->getStatus());
        self::assertEquals($paymentEntity->getIdSalesPayment(), $expenseRefundTransfer->getFkSalesPayment());
        self::assertEquals($expenseTransfer->getIdSalesExpense(), $expenseRefundTransfer->getFkSalesExpense());
    }

    /**
     * @return void
     */
    public function testRefundCalculationWithAdyenAndBenefitVoucherPaymentMethod(): void
    {
        $salesOrder = $this->tester->createSalesOrderWithItems([
            array_merge(self::ITEM_1_SEED_DATA, [
                ItemTransfer::USE_BENEFIT_VOUCHER => true,
                ItemTransfer::TOTAL_USED_BENEFIT_VOUCHERS_AMOUNT => 200,
            ]),
            self::ITEM_2_SEED_DATA,
        ]);
        $adyenPaymentEntity = $this->tester->createAdyenPayment($salesOrder->getIdSalesOrder(), 1650);
        $benefitVoucherPaymentEntity = $this->tester->createBenefitVoucherPayment($salesOrder->getIdSalesOrder(), 200);
        $expenseEntity = $this->tester->createExpense($salesOrder->getIdSalesOrder(), 450);

        $refundTransfer = $this->sut->calculateRefund($salesOrder->getItems()->getData(), $salesOrder);

        // Asserting first item refunds
        self::assertCount(2, $refundTransfer->getItems()[0]->getRefunds());
        $adyenItemRefund = $this->findRefundInCollectionByIdPayment(
            $adyenPaymentEntity->getIdSalesPayment(),
            $refundTransfer->getItems()[0]->getRefunds()
        );
        self::assertNotNull($adyenItemRefund);
        self::assertEquals(800, $adyenItemRefund->getAmount());
        $benefitVoucherItemRefund = $this->findRefundInCollectionByIdPayment(
            $benefitVoucherPaymentEntity->getIdSalesPayment(),
            $refundTransfer->getItems()[0]->getRefunds()
        );
        self::assertNotNull($benefitVoucherItemRefund);
        self::assertEquals(200, $benefitVoucherItemRefund->getAmount());

        // Asserting second item refund
        self::assertCount(1, $refundTransfer->getItems()[1]->getRefunds());
        $adyenItemRefund = $refundTransfer->getItems()[1]->getRefunds()[0];
        self::assertNotNull($adyenItemRefund);
        self::assertEquals(400, $adyenItemRefund->getAmount());
        self::assertEquals($adyenPaymentEntity->getIdSalesPayment(), $adyenItemRefund->getFkSalesPayment());

        // Asserting expense refund
        $expenseTransfer = $this->findExpenseInCollectionById(
            $expenseEntity->getIdSalesExpense(),
            $refundTransfer->getExpenses()
        );
        self::assertCount(1, $expenseTransfer->getRefunds());
        $expenseRefundTransfer = $expenseTransfer->getRefunds()[0];
        self::assertEquals(450, $expenseRefundTransfer->getAmount());
        self::assertEquals($adyenPaymentEntity->getIdSalesPayment(), $expenseRefundTransfer->getFkSalesPayment());
    }

    /**
     * @return void
     */
    public function testRefundCalculationWithMultiplePaymentMethods(): void
    {
        $salesOrder = $this->tester->createSalesOrderWithItems([
            array_merge(self::ITEM_1_SEED_DATA, [
                ItemTransfer::USE_BENEFIT_VOUCHER => true,
                ItemTransfer::TOTAL_USED_BENEFIT_VOUCHERS_AMOUNT => 200,
            ]),
            self::ITEM_2_SEED_DATA,
        ]);
        $adyenPaymentEntity = $this->tester->createAdyenPayment($salesOrder->getIdSalesOrder(), 1250);
        $benefitVoucherPaymentEntity = $this->tester->createBenefitVoucherPayment($salesOrder->getIdSalesOrder(), 200);
        $cashbackPaymentEntity = $this->tester->createCashbackPayment($salesOrder->getIdSalesOrder(), 400);
        $expenseEntity = $this->tester->createExpense($salesOrder->getIdSalesOrder(), 450);

        $refundTransfer = $this->sut->calculateRefund($salesOrder->getItems()->getData(), $salesOrder);

        // Asserting first item refunds
        self::assertCount(2, $refundTransfer->getItems()[0]->getRefunds());
        $adyenItemRefund = $this->findRefundInCollectionByIdPayment(
            $adyenPaymentEntity->getIdSalesPayment(),
            $refundTransfer->getItems()[0]->getRefunds()
        );
        self::assertNotNull($adyenItemRefund);
        self::assertEquals(800, $adyenItemRefund->getAmount());
        $benefitVoucherItemRefund = $this->findRefundInCollectionByIdPayment(
            $benefitVoucherPaymentEntity->getIdSalesPayment(),
            $refundTransfer->getItems()[0]->getRefunds()
        );
        self::assertNotNull($benefitVoucherItemRefund);
        self::assertEquals(200, $benefitVoucherItemRefund->getAmount());

        // Asserting second item refund
        self::assertCount(1, $refundTransfer->getItems()[1]->getRefunds());
        $adyenItemRefund = $refundTransfer->getItems()[1]->getRefunds()[0];
        self::assertNotNull($adyenItemRefund);
        self::assertEquals(400, $adyenItemRefund->getAmount());
        self::assertEquals($adyenPaymentEntity->getIdSalesPayment(), $adyenItemRefund->getFkSalesPayment());

        // Asserting expense refund
        $expenseTransfer = $this->findExpenseInCollectionById(
            $expenseEntity->getIdSalesExpense(),
            $refundTransfer->getExpenses()
        );
        self::assertCount(2, $expenseTransfer->getRefunds());
        $adyenExpenseRefundTransfer = $this->findRefundInCollectionByIdPayment(
            $adyenPaymentEntity->getIdSalesPayment(),
            $expenseTransfer->getRefunds()
        );
        self::assertNotNull($adyenExpenseRefundTransfer);
        self::assertEquals(50, $adyenExpenseRefundTransfer->getAmount());
        $cashbackExpenseRefundTransfer = $this->findRefundInCollectionByIdPayment(
            $cashbackPaymentEntity->getIdSalesPayment(),
            $expenseTransfer->getRefunds()
        );
        self::assertNotNull($cashbackExpenseRefundTransfer);
        self::assertEquals(400, $cashbackExpenseRefundTransfer->getAmount());
    }

    /**
     * @return void
     */
    public function testRefundCalculationWithEVoucherAndBenefitVoucherPaymentMethod(): void
    {
        $salesOrder = $this->tester->createSalesOrderWithItems([
            self::ITEM_1_SEED_DATA,
            array_merge(self::ITEM_2_SEED_DATA, [
                ItemTransfer::USE_BENEFIT_VOUCHER => true,
                ItemTransfer::TOTAL_USED_BENEFIT_VOUCHERS_AMOUNT => 200,
            ]),
        ]);
        $eVoucherPayment = $this->tester->createEVoucherPayment($salesOrder->getIdSalesOrder(), 1650);
        $benefitVoucherPaymentEntity = $this->tester->createBenefitVoucherPayment($salesOrder->getIdSalesOrder(), 200);
        $expenseEntity = $this->tester->createExpense($salesOrder->getIdSalesOrder(), 450);

        $refundTransfer = $this->sut->calculateRefund($salesOrder->getItems()->getData(), $salesOrder);

        // Asserting first item refunds
        self::assertCount(1, $refundTransfer->getItems()[0]->getRefunds());
        $eVoucherItemRefund = $refundTransfer->getItems()[0]->getRefunds()[0];
        self::assertEquals(1000, $eVoucherItemRefund->getAmount());
        self::assertEquals($eVoucherPayment->getIdSalesPayment(), $eVoucherItemRefund->getFkSalesPayment());

        // Asserting second item refund
        self::assertCount(2, $refundTransfer->getItems()[1]->getRefunds());
        $eVoucherItemRefund = $this->findRefundInCollectionByIdPayment(
            $eVoucherPayment->getIdSalesPayment(),
            $refundTransfer->getItems()[1]->getRefunds()
        );
        self::assertNotNull($eVoucherItemRefund);
        self::assertEquals(200, $eVoucherItemRefund->getAmount());
        $benefitVoucherItemRefund = $this->findRefundInCollectionByIdPayment(
            $benefitVoucherPaymentEntity->getIdSalesPayment(),
            $refundTransfer->getItems()[1]->getRefunds()
        );
        self::assertNotNull($benefitVoucherItemRefund);
        self::assertEquals(200, $benefitVoucherItemRefund->getAmount());

        // Asserting expense refund
        $expenseTransfer = $this->findExpenseInCollectionById(
            $expenseEntity->getIdSalesExpense(),
            $refundTransfer->getExpenses()
        );
        self::assertCount(1, $expenseTransfer->getRefunds());
        $expenseRefundTransfer = $expenseTransfer->getRefunds()[0];
        self::assertEquals(450, $expenseRefundTransfer->getAmount());
        self::assertEquals($eVoucherPayment->getIdSalesPayment(), $expenseRefundTransfer->getFkSalesPayment());
    }

    /**
     * @return void
     */
    public function testRefundCalculationWithAdyenAndEVoucherOnBehalfOfMarketerPaymentMethod(): void
    {
        $salesOrder = $this->tester->createSalesOrderWithItems([self::ITEM_1_SEED_DATA, self::ITEM_2_SEED_DATA]);
        $adyenPayment = $this->tester->createAdyenPayment($salesOrder->getIdSalesOrder(), 600);
        $marketerEVoucherPayment = $this->tester->createMarketerEVoucherPayment($salesOrder->getIdSalesOrder(), 1250);
        $expenseEntity = $this->tester->createExpense($salesOrder->getIdSalesOrder(), 450);

        $refundTransfer = $this->sut->calculateRefund($salesOrder->getItems()->getData(), $salesOrder);

        // Asserting first item refunds
        self::assertCount(2, $refundTransfer->getItems()[0]->getRefunds());
        $adyenItemRefund = $this->findRefundInCollectionByIdPayment(
            $adyenPayment->getIdSalesPayment(),
            $refundTransfer->getItems()[0]->getRefunds()
        );
        self::assertEquals(600, $adyenItemRefund->getAmount());
        $marketerEVoucherItemRefund = $this->findRefundInCollectionByIdPayment(
            $marketerEVoucherPayment->getIdSalesPayment(),
            $refundTransfer->getItems()[0]->getRefunds()
        );
        self::assertEquals(400, $marketerEVoucherItemRefund->getAmount());

        // Asserting second item refund
        self::assertCount(1, $refundTransfer->getItems()[1]->getRefunds());
        $marketerEVoucherItemRefund = $refundTransfer->getItems()[1]->getRefunds()[0];
        self::assertEquals(400, $marketerEVoucherItemRefund->getAmount());
        self::assertEquals($marketerEVoucherPayment->getIdSalesPayment(), $marketerEVoucherItemRefund->getFkSalesPayment());

        // Asserting expense refund
        $expenseTransfer = $this->findExpenseInCollectionById(
            $expenseEntity->getIdSalesExpense(),
            $refundTransfer->getExpenses()
        );
        self::assertCount(1, $expenseTransfer->getRefunds());
        $expenseRefundTransfer = $expenseTransfer->getRefunds()[0];
        self::assertEquals(450, $expenseRefundTransfer->getAmount());
        self::assertEquals($marketerEVoucherPayment->getIdSalesPayment(), $expenseRefundTransfer->getFkSalesPayment());
    }

    /**
     * @return void
     */
    public function testRefundCalculationForSingleItemWithAdyenPaymentMethod(): void
    {
        $salesOrder = $this->tester->createSalesOrderWithItems([self::ITEM_1_SEED_DATA, self::ITEM_2_SEED_DATA]);
        $expenseEntity = $this->tester->createExpense($salesOrder->getIdSalesOrder(), 450);
        $adyenPayment = $this->tester->createAdyenPayment($salesOrder->getIdSalesOrder(), 1850);

        $refundTransfer = $this->sut->calculateRefund(array_slice($salesOrder->getItems()->getData(), 1), $salesOrder);

        // Assert single item refund
        self::assertCount(1, $refundTransfer->getItems());
        self::assertCount(1, $refundTransfer->getItems()[0]->getRefunds());
        $adyenItemRefund = $refundTransfer->getItems()[0]->getRefunds()[0];
        self::assertEquals(400, $adyenItemRefund->getAmount());
        self::assertEquals($adyenPayment->getIdSalesPayment(), $adyenItemRefund->getFkSalesPayment());

        // Assert expense not being refunded
        $expenseTransfer = $this->findExpenseInCollectionById(
            $expenseEntity->getIdSalesExpense(),
            $refundTransfer->getExpenses()
        );
        self::assertNull($expenseTransfer);
    }

    /**
     * @return void
     */
    public function testRefundCalculationForSingleItemWithAdyenAndBenefitVoucherPaymentMethods(): void
    {
        $salesOrder = $this->tester->createSalesOrderWithItems([
            array_merge(self::ITEM_1_SEED_DATA, [
                ItemTransfer::USE_BENEFIT_VOUCHER => true,
                ItemTransfer::TOTAL_USED_BENEFIT_VOUCHERS_AMOUNT => 400,
            ]),
            self::ITEM_2_SEED_DATA,
        ]);
        $expenseEntity = $this->tester->createExpense($salesOrder->getIdSalesOrder(), 450);
        $adyenPayment = $this->tester->createAdyenPayment($salesOrder->getIdSalesOrder(), 1450);
        $benefitVoucherPayment = $this->tester->createBenefitVoucherPayment($salesOrder->getIdSalesOrder(), 400);

        $refundTransfer = $this->sut->calculateRefund(
            array_slice($salesOrder->getItems()->getData(), 0, 1),
            $salesOrder
        );

        // Assert single item refund
        self::assertCount(1, $refundTransfer->getItems());
        self::assertCount(2, $refundTransfer->getItems()[0]->getRefunds());
        $adyenItemRefund = $this->findRefundInCollectionByIdPayment(
            $adyenPayment->getIdSalesPayment(),
            $refundTransfer->getItems()[0]->getRefunds()
        );
        self::assertEquals(600, $adyenItemRefund->getAmount());
        $benefitVoucherItemRefund = $this->findRefundInCollectionByIdPayment(
            $benefitVoucherPayment->getIdSalesPayment(),
            $refundTransfer->getItems()[0]->getRefunds()
        );
        self::assertEquals(400, $benefitVoucherItemRefund->getAmount());

        // Assert expense not being refunded
        $expenseTransfer = $this->findExpenseInCollectionById(
            $expenseEntity->getIdSalesExpense(),
            $refundTransfer->getExpenses()
        );
        self::assertNull($expenseTransfer);
    }

    /**
     * @return void
     */
    public function testRefundCalculationForSingleItemWithMultiplePaymentMethods(): void
    {
        $salesOrder = $this->tester->createSalesOrderWithItems([
            array_merge(self::ITEM_1_SEED_DATA, [
                ItemTransfer::USE_BENEFIT_VOUCHER => true,
                ItemTransfer::TOTAL_USED_BENEFIT_VOUCHERS_AMOUNT => 400,
            ]),
            self::ITEM_2_SEED_DATA,
        ]);
        $expenseEntity = $this->tester->createExpense($salesOrder->getIdSalesOrder(), 450);
        $adyenPayment = $this->tester->createAdyenPayment($salesOrder->getIdSalesOrder(), 500);
        $cashbackPayment = $this->tester->createCashbackPayment($salesOrder->getIdSalesOrder(), 950);
        $benefitVoucherPayment = $this->tester->createBenefitVoucherPayment($salesOrder->getIdSalesOrder(), 400);

        $refundTransfer = $this->sut->calculateRefund(
            array_slice($salesOrder->getItems()->getData(), 0, 1),
            $salesOrder
        );

        // Assert single item refund
        self::assertCount(1, $refundTransfer->getItems());
        self::assertCount(3, $refundTransfer->getItems()[0]->getRefunds());
        $adyenItemRefund = $this->findRefundInCollectionByIdPayment(
            $adyenPayment->getIdSalesPayment(),
            $refundTransfer->getItems()[0]->getRefunds()
        );
        self::assertEquals(500, $adyenItemRefund->getAmount());
        $benefitVoucherItemRefund = $this->findRefundInCollectionByIdPayment(
            $benefitVoucherPayment->getIdSalesPayment(),
            $refundTransfer->getItems()[0]->getRefunds()
        );
        self::assertEquals(400, $benefitVoucherItemRefund->getAmount());
        $cashbackItemRefund = $this->findRefundInCollectionByIdPayment(
            $cashbackPayment->getIdSalesPayment(),
            $refundTransfer->getItems()[0]->getRefunds()
        );
        self::assertEquals(100, $cashbackItemRefund->getAmount());

        // Assert expense not being refunded
        $expenseTransfer = $this->findExpenseInCollectionById(
            $expenseEntity->getIdSalesExpense(),
            $refundTransfer->getExpenses()
        );
        self::assertNull($expenseTransfer);
    }

    /**
     * @return void
     */
    public function testRefundCalculationForLastRefundableItemWithAdyenPaymentMethods(): void
    {
        $salesOrder = $this->tester->createSalesOrderWithItems([
            array_merge(self::ITEM_1_SEED_DATA, [
                ItemTransfer::REFUNDABLE_AMOUNT => 0,
                ItemTransfer::CANCELED_AMOUNT => 1000,
            ]),
            self::ITEM_2_SEED_DATA,
        ]);
        $expenseEntity = $this->tester->createExpense($salesOrder->getIdSalesOrder(), 450);
        $adyenPayment = $this->tester->createAdyenPayment($salesOrder->getIdSalesOrder(), 1850);

        $refundTransfer = $this->sut->calculateRefund(
            array_slice($salesOrder->getItems()->getData(), 1),
            $salesOrder
        );

        // Assert single item refund
        self::assertCount(1, $refundTransfer->getItems());
        self::assertCount(1, $refundTransfer->getItems()[0]->getRefunds());
        $adyenItemRefund = $refundTransfer->getItems()[0]->getRefunds()[0];
        self::assertEquals(400, $adyenItemRefund->getAmount());
        self::assertEquals($adyenPayment->getIdSalesPayment(), $adyenItemRefund->getFkSalesPayment());

        // Assert expense refund
        $expenseTransfer = $this->findExpenseInCollectionById(
            $expenseEntity->getIdSalesExpense(),
            $refundTransfer->getExpenses()
        );
        self::assertNotNull($expenseTransfer);
        self::assertCount(1, $expenseTransfer->getRefunds());
        $adyenExpenseRefund = $expenseTransfer->getRefunds()[0];
        self::assertEquals(450, $adyenExpenseRefund->getAmount());
        self::assertEquals($adyenPayment->getIdSalesPayment(), $adyenExpenseRefund->getFkSalesPayment());
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
    private function findRefundInCollectionByIdPayment(int $idSalesPayment, iterable $refunds)
    {
        foreach ($refunds as $refundTransfer) {
            if ($refundTransfer->getFkSalesPayment() === $idSalesPayment) {
                return $refundTransfer;
            }
        }

        return null;
    }
}
