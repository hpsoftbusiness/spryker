<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Zed\Adyen\Communication\Plugin\Refund;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ExpenseRefundTransfer;
use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\ItemRefundTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Pyz\Shared\Adyen\AdyenConfig;
use Pyz\Shared\DummyPrepayment\DummyPrepaymentConfig;
use Pyz\Shared\MyWorldPayment\MyWorldPaymentConfig;
use Pyz\Zed\Adyen\Communication\Plugin\Refund\AdyenRefundCalculatorPlugin;
use Pyz\Zed\Refund\RefundConfig;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Zed
 * @group Adyen
 * @group Communication
 * @group Plugin
 * @group Refund
 * @group AdyenRefundCalculatorPluginTest
 * Add your own group annotations below this line
 */
class AdyenRefundCalculatorPluginTest extends Unit
{
    /**
     * @var \PyzTest\Zed\Adyen\AdyenCommunicationTester
     */
    protected $tester;

    /**
     * @var \Pyz\Zed\Adyen\Communication\Plugin\Refund\AdyenRefundCalculatorPlugin
     */
    private $sut;

    /**
     * @return void
     */
    protected function _before(): void
    {
        $this->sut = $this->createAdyenRefundCalculatorPlugin();
    }

    /**
     * @dataProvider provideItemRefundData
     *
     * @param array $itemData
     * @param array $paymentData
     * @param array $expectedItemRefundData
     * @param int $expectedPaymentRefundableAmount
     *
     * @return void
     */
    public function testItemRefundCalculation(
        array $itemData,
        array $paymentData,
        array $expectedItemRefundData,
        int $expectedPaymentRefundableAmount
    ): void {
        $itemTransfer = $this->tester->buildItemTransfer($itemData);
        $paymentTransfer = $this->tester->buildPaymentTransfer($paymentData);

        $itemRefundTransfer = $this->sut->calculateItemRefund($itemTransfer, $paymentTransfer);

        self::assertNotNull($itemRefundTransfer);

        $itemRefundData = $itemRefundTransfer->toArrayNotRecursiveCamelCased();
        foreach ($expectedItemRefundData as $key => $expectedData) {
            self::assertEquals($expectedData, $itemRefundData[$key]);
        }

        self::assertEquals($expectedPaymentRefundableAmount, $paymentTransfer->getRefundableAmount());
    }

    /**
     * @dataProvider provideExpenseRefundData
     *
     * @param array $expenseData
     * @param array $paymentData
     * @param array $expectedExpenseRefundData
     * @param int $expectedPaymentRefundableAmount
     *
     * @return void
     */
    public function testExpenseRefundCalculation(
        array $expenseData,
        array $paymentData,
        array $expectedExpenseRefundData,
        int $expectedPaymentRefundableAmount
    ): void {
        $expenseTransfer = $this->tester->buildExpenseTransfer($expenseData);
        $paymentTransfer = $this->tester->buildPaymentTransfer($paymentData);

        $expenseRefundTransfer = $this->sut->calculateExpenseRefund($expenseTransfer, $paymentTransfer);

        self::assertNotNull($expenseRefundTransfer);

        $itemRefundData = $expenseRefundTransfer->toArrayNotRecursiveCamelCased();
        foreach ($expectedExpenseRefundData as $key => $expectedData) {
            self::assertEquals($expectedData, $itemRefundData[$key]);
        }

        self::assertEquals($expectedPaymentRefundableAmount, $paymentTransfer->getRefundableAmount());
    }

    /**
     * @return void
     */
    public function testFindsAdyenPaymentTransfer(): void
    {
        $paymentsCollection = [
            $this->tester->buildPaymentTransfer([PaymentTransfer::PAYMENT_PROVIDER => DummyPrepaymentConfig::DUMMY_PREPAYMENT]),
            $this->tester->buildPaymentTransfer([PaymentTransfer::PAYMENT_PROVIDER => AdyenConfig::PROVIDER_NAME]),
            $this->tester->buildPaymentTransfer([PaymentTransfer::PAYMENT_PROVIDER => MyWorldPaymentConfig::PAYMENT_PROVIDER_NAME_MY_WORLD]),
        ];

        $paymentTransfer = $this->sut->findApplicablePayment($paymentsCollection);

        self::assertNotNull($paymentTransfer);
        self::assertEquals(AdyenConfig::PROVIDER_NAME, $paymentTransfer->getPaymentProvider());
    }

    /**
     * @return void
     */
    public function testReturnsNullIfAdyenPaymentNotFound(): void
    {
        $paymentsCollection = [
            $this->tester->buildPaymentTransfer([PaymentTransfer::PAYMENT_PROVIDER => DummyPrepaymentConfig::DUMMY_PREPAYMENT]),
            $this->tester->buildPaymentTransfer([PaymentTransfer::PAYMENT_PROVIDER => MyWorldPaymentConfig::PAYMENT_PROVIDER_NAME_MY_WORLD]),
        ];

        $paymentTransfer = $this->sut->findApplicablePayment($paymentsCollection);

        self::assertNull($paymentTransfer);
    }

    /**
     * @return array[]
     */
    public function provideItemRefundData(): array
    {
        return [
            'item refunded for full amount' => [
                'item' => [
                    ItemTransfer::CANCELED_AMOUNT => 1000,
                    ItemTransfer::ID_SALES_ORDER_ITEM => 1,
                ],
                'payment' => [
                    PaymentTransfer::REFUNDABLE_AMOUNT => 1500,
                    PaymentTransfer::ID_SALES_PAYMENT => 2,
                ],
                'expected refund data' => [
                    ItemRefundTransfer::AMOUNT => 1000,
                    ItemRefundTransfer::STATUS => RefundConfig::PAYMENT_REFUND_STATUS_NEW,
                    ItemRefundTransfer::FK_SALES_ORDER_ITEM => 1,
                    ItemRefundTransfer::FK_SALES_PAYMENT => 2,
                ],
                'expected refundable amount after calculation' => 500,
            ],
            'item refunded for available refundable amount' => [
                'item' => [
                    ItemTransfer::CANCELED_AMOUNT => 1000,
                    ItemTransfer::ID_SALES_ORDER_ITEM => 1,
                ],
                'payment' => [
                    PaymentTransfer::REFUNDABLE_AMOUNT => 800,
                    PaymentTransfer::ID_SALES_PAYMENT => 2,
                ],
                'expected refund data' => [
                    ItemRefundTransfer::AMOUNT => 800,
                    ItemRefundTransfer::STATUS => RefundConfig::PAYMENT_REFUND_STATUS_NEW,
                    ItemRefundTransfer::FK_SALES_ORDER_ITEM => 1,
                    ItemRefundTransfer::FK_SALES_PAYMENT => 2,
                ],
                'expected refundable amount after calculation' => 0,
            ],
        ];
    }

    /**
     * @return array[]
     */
    public function provideExpenseRefundData(): array
    {
        return [
            'expense refunded for full amount' => [
                'expense' => [
                    ExpenseTransfer::CANCELED_AMOUNT => 1000,
                    ExpenseTransfer::ID_SALES_EXPENSE => 1,
                ],
                'payment' => [
                    PaymentTransfer::REFUNDABLE_AMOUNT => 1500,
                    PaymentTransfer::ID_SALES_PAYMENT => 2,
                ],
                'expected refund data' => [
                    ExpenseRefundTransfer::AMOUNT => 1000,
                    ExpenseRefundTransfer::STATUS => RefundConfig::PAYMENT_REFUND_STATUS_NEW,
                    ExpenseRefundTransfer::FK_SALES_EXPENSE => 1,
                    ExpenseRefundTransfer::FK_SALES_PAYMENT => 2,
                ],
                'expected refundable amount after calculation' => 500,
            ],
            'expense refunded for available refundable amount' => [
                'expense' => [
                    ExpenseTransfer::CANCELED_AMOUNT => 1000,
                    ExpenseTransfer::ID_SALES_EXPENSE => 1,
                ],
                'payment' => [
                    PaymentTransfer::REFUNDABLE_AMOUNT => 800,
                    PaymentTransfer::ID_SALES_PAYMENT => 2,
                ],
                'expected refund data' => [
                    ExpenseRefundTransfer::AMOUNT => 800,
                    ExpenseRefundTransfer::STATUS => RefundConfig::PAYMENT_REFUND_STATUS_NEW,
                    ExpenseRefundTransfer::FK_SALES_EXPENSE => 1,
                    ExpenseRefundTransfer::FK_SALES_PAYMENT => 2,
                ],
                'expected refundable amount after calculation' => 0,
            ],
        ];
    }

    /**
     * @return \Pyz\Zed\Adyen\Communication\Plugin\Refund\AdyenRefundCalculatorPlugin
     */
    private function createAdyenRefundCalculatorPlugin(): AdyenRefundCalculatorPlugin
    {
        return new AdyenRefundCalculatorPlugin();
    }
}
