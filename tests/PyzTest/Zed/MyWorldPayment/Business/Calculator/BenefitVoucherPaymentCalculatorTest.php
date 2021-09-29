<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Zed\MyWorldPayment\Business\Calculator;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\CalculableObjectBuilder;
use Generated\Shared\Transfer\BenefitVoucherDealDataTransfer;
use Generated\Shared\Transfer\CalculableObjectTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Pyz\Zed\MyWorldPayment\Business\Calculator\BenefitVoucherPaymentCalculator;
use Pyz\Zed\MyWorldPayment\MyWorldPaymentConfig;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Zed
 * @group MyWorldPayment
 * @group Business
 * @group Calculator
 * @group BenefitVoucherPaymentCalculatorTest
 * Add your own group annotations below this line
 */
class BenefitVoucherPaymentCalculatorTest extends Unit
{
    /**
     * @var \PyzTest\Zed\MyWorldPayment\MyWorldPaymentBusinessTester
     */
    protected $tester;

    /**
     * @var \Pyz\Zed\MyWorldPayment\Business\Calculator\BenefitVoucherPaymentCalculator
     */
    private $sut;

    /**
     * @return void
     */
    protected function _before(): void
    {
        $this->sut = $this->createBenefitVoucherPaymentCalculator();
    }

    /**
     * @dataProvider provideItemData
     *
     * @param array $calculableObjectData
     * @param array $itemExpectedTotalUsedBenefitVoucherAmount
     * @param int|null $expectedTotalUsedBenefitVoucherAmount
     *
     * @return void
     */
    public function testRecalculateQuote(
        array $calculableObjectData,
        array $itemExpectedTotalUsedBenefitVoucherAmount,
        ?int $expectedTotalUsedBenefitVoucherAmount
    ): void {
        $calculableObjectTransfer = $this->buildCalculableObjectTransfer($calculableObjectData);

        $calculableObjectTransfer = $this->sut->recalculateQuote($calculableObjectTransfer);

        foreach ($calculableObjectTransfer->getItems() as $index => $itemTransfer) {
            self::assertEquals(
                $itemExpectedTotalUsedBenefitVoucherAmount[$index],
                $itemTransfer->getTotalUsedBenefitVouchersAmount()
            );
        }

        self::assertEquals(
            $expectedTotalUsedBenefitVoucherAmount,
            $calculableObjectTransfer->getTotalUsedBenefitVouchersAmount()
        );

        $paymentTransfer = $this->findBenefitVoucherPaymentMethod($calculableObjectTransfer);
        if (!$expectedTotalUsedBenefitVoucherAmount) {
            self::assertNull($paymentTransfer);
        } else {
            self::assertEquals($expectedTotalUsedBenefitVoucherAmount, $paymentTransfer->getAmount());
        }
    }

    /**
     * @return void
     */
    public function testRecalculateOrderSetsBenefitPrice(): void
    {
        $calculableObjectTransfer = $this->buildCalculableObjectTransfer([
            CalculableObjectTransfer::ITEMS => [
                [
                    ItemTransfer::USE_BENEFIT_VOUCHER => true,
                    ItemTransfer::TOTAL_USED_BENEFIT_VOUCHERS_AMOUNT => 200,
                    ItemTransfer::QUANTITY => 1,
                    ItemTransfer::UNIT_GROSS_PRICE => 600,
                ],
                [
                    ItemTransfer::TOTAL_USED_BENEFIT_VOUCHERS_AMOUNT => null,
                    ItemTransfer::QUANTITY => 1,
                    ItemTransfer::UNIT_GROSS_PRICE => 600,
                ],
                [
                    ItemTransfer::USE_BENEFIT_VOUCHER => true,
                    ItemTransfer::TOTAL_USED_BENEFIT_VOUCHERS_AMOUNT => 200,
                    ItemTransfer::QUANTITY => 2,
                    ItemTransfer::UNIT_GROSS_PRICE => 600,
                ],
            ],
        ]);

        $this->sut->recalculateOrder($calculableObjectTransfer);

        self::assertEquals(400, $calculableObjectTransfer->getItems()[0]->getUnitBenefitPrice());
        self::assertEquals(400, $calculableObjectTransfer->getItems()[0]->getSumBenefitPrice());

        self::assertNull($calculableObjectTransfer->getItems()[1]->getUnitBenefitPrice());
        self::assertNull($calculableObjectTransfer->getItems()[1]->getSumBenefitPrice());

        self::assertEquals(500, $calculableObjectTransfer->getItems()[2]->getUnitBenefitPrice());
        self::assertEquals(1000, $calculableObjectTransfer->getItems()[2]->getSumBenefitPrice());
    }

    /**
     * @return array
     */
    public function provideItemData(): array
    {
        return [
            'single item with Benefit Voucher' => [
                'calculableObjectData' => [
                    CalculableObjectTransfer::USE_BENEFIT_VOUCHER => true,
                    CalculableObjectTransfer::TOTAL_USED_BENEFIT_VOUCHERS_AMOUNT => 600,
                    CalculableObjectTransfer::ITEMS => [
                        [
                            ItemTransfer::QUANTITY => 2,
                            ItemTransfer::USE_BENEFIT_VOUCHER => true,
                            ItemTransfer::BENEFIT_VOUCHER_DEAL_DATA => [
                                BenefitVoucherDealDataTransfer::AMOUNT => 200,
                                BenefitVoucherDealDataTransfer::SALES_PRICE => 800,
                                BenefitVoucherDealDataTransfer::IS_STORE => true,
                            ],
                            ItemTransfer::TOTAL_USED_BENEFIT_VOUCHERS_AMOUNT => 200,
                        ],
                    ],
                ],
                'expectedItemAmount' => [
                    400,
                ],
                'expectedTotalAmount' => 400,
            ],
            'single item with lower quantity and Benefit Voucher' => [
                'calculableObjectData' => [
                    CalculableObjectTransfer::USE_BENEFIT_VOUCHER => true,
                    CalculableObjectTransfer::TOTAL_USED_BENEFIT_VOUCHERS_AMOUNT => 300,
                    CalculableObjectTransfer::ITEMS => [
                        [
                            ItemTransfer::QUANTITY => 2,
                            ItemTransfer::USE_BENEFIT_VOUCHER => true,
                            ItemTransfer::BENEFIT_VOUCHER_DEAL_DATA => [
                                BenefitVoucherDealDataTransfer::AMOUNT => 200,
                                BenefitVoucherDealDataTransfer::SALES_PRICE => 800,
                                BenefitVoucherDealDataTransfer::IS_STORE => true,
                            ],
                        ],
                        [
                            ItemTransfer::QUANTITY => 1,
                            ItemTransfer::TOTAL_USED_BENEFIT_VOUCHERS_AMOUNT => 400,
                        ],
                    ],
                ],
                'expectedItemAmount' => [
                    300,
                    null,
                ],
                'expectedTotalAmount' => 300,
            ],
            'multiple items with Benefit Voucher' => [
                'calculableObjectData' => [
                    CalculableObjectTransfer::USE_BENEFIT_VOUCHER => true,
                    CalculableObjectTransfer::TOTAL_USED_BENEFIT_VOUCHERS_AMOUNT => 720,
                    CalculableObjectTransfer::ITEMS => [
                        [
                            ItemTransfer::QUANTITY => 2,
                            ItemTransfer::BENEFIT_VOUCHER_DEAL_DATA => [
                                BenefitVoucherDealDataTransfer::AMOUNT => 200,
                                BenefitVoucherDealDataTransfer::SALES_PRICE => 800,
                                BenefitVoucherDealDataTransfer::IS_STORE => true,
                            ],
                        ],
                        [
                            ItemTransfer::QUANTITY => 1,
                            ItemTransfer::BENEFIT_VOUCHER_DEAL_DATA => [
                                BenefitVoucherDealDataTransfer::AMOUNT => 300,
                                BenefitVoucherDealDataTransfer::SALES_PRICE => 800,
                                BenefitVoucherDealDataTransfer::IS_STORE => true,
                            ],
                        ],
                        [
                            ItemTransfer::QUANTITY => 1,
                        ],
                        [
                            ItemTransfer::QUANTITY => 3,
                            ItemTransfer::BENEFIT_VOUCHER_DEAL_DATA => [
                                BenefitVoucherDealDataTransfer::AMOUNT => 80,
                                BenefitVoucherDealDataTransfer::SALES_PRICE => 800,
                                BenefitVoucherDealDataTransfer::IS_STORE => true,
                            ],
                        ],
                    ],
                ],
                'expectedAmount' => [
                    400,
                    300,
                    null,
                    20,
                ],
                'expectedTotalAmount' => 720,
            ],
            'item missing Benefit Voucher data' => [
                'calculableObjectData' => [
                    CalculableObjectTransfer::TOTAL_USED_BENEFIT_VOUCHERS_AMOUNT => 300,
                    CalculableObjectTransfer::USE_BENEFIT_VOUCHER => true,
                    CalculableObjectTransfer::ITEMS => [
                        [
                            ItemTransfer::QUANTITY => 1,
                            ItemTransfer::USE_BENEFIT_VOUCHER => true,
                        ],
                    ],
                ],
                'expectedAmount' => [
                    null,
                ],
                'expectedTotalAmount' => null,
            ],
            'quote use benefit voucher flag not set' => [
                'calculableObjectData' => [
                    CalculableObjectTransfer::TOTAL_USED_BENEFIT_VOUCHERS_AMOUNT => 300,
                    CalculableObjectTransfer::ITEMS => [
                        [
                            ItemTransfer::QUANTITY => 1,
                            ItemTransfer::BENEFIT_VOUCHER_DEAL_DATA => [
                                BenefitVoucherDealDataTransfer::AMOUNT => 200,
                                BenefitVoucherDealDataTransfer::SALES_PRICE => 800,
                                BenefitVoucherDealDataTransfer::IS_STORE => true,
                            ],
                        ],
                    ],
                ],
                'expectedAmount' => [
                    null,
                ],
                'expectedTotalAmount' => null,
            ],
            'item not flagged for item Benefit Voucher gets cleared' => [
                'calculableObjectData' => [
                    CalculableObjectTransfer::ITEMS => [
                        [
                            ItemTransfer::QUANTITY => 1,
                            ItemTransfer::BENEFIT_VOUCHER_DEAL_DATA => [
                                BenefitVoucherDealDataTransfer::AMOUNT => 100,
                                BenefitVoucherDealDataTransfer::SALES_PRICE => 800,
                                BenefitVoucherDealDataTransfer::IS_STORE => true,
                            ],
                            ItemTransfer::TOTAL_USED_BENEFIT_VOUCHERS_AMOUNT => 200,
                        ],
                    ],
                ],
                'expectedAmount' => [
                    null,
                ],
                'expectedTotalAmount' => null,
            ],
            'payment method removed if Benefit Voucher not applied' => [
                'calculableObjectData' => [
                    CalculableObjectTransfer::ITEMS => [
                        [
                            ItemTransfer::QUANTITY => 1,
                            ItemTransfer::BENEFIT_VOUCHER_DEAL_DATA => [
                                BenefitVoucherDealDataTransfer::AMOUNT => 100,
                                BenefitVoucherDealDataTransfer::SALES_PRICE => 800,
                                BenefitVoucherDealDataTransfer::IS_STORE => true,
                            ],
                        ],
                    ],
                    CalculableObjectTransfer::PAYMENTS => [
                        [
                            PaymentTransfer::PAYMENT_METHOD => MyWorldPaymentConfig::PAYMENT_METHOD_BENEFIT_VOUCHER_NAME,
                            PaymentTransfer::PAYMENT_SELECTION => MyWorldPaymentConfig::PAYMENT_METHOD_BENEFIT_VOUCHER_NAME,
                            PaymentTransfer::AMOUNT => 100,
                        ],
                    ],
                ],
                'expectedAmount' => [
                    null,
                ],
                'expectedTotalAmount' => null,
            ],
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentTransfer|null
     */
    private function findBenefitVoucherPaymentMethod(CalculableObjectTransfer $calculableObjectTransfer): ?PaymentTransfer
    {
        foreach ($calculableObjectTransfer->getPayments() as $paymentTransfer) {
            if ($paymentTransfer->getPaymentMethod() === MyWorldPaymentConfig::PAYMENT_METHOD_BENEFIT_VOUCHER_NAME) {
                return $paymentTransfer;
            }
        }

        return null;
    }

    /**
     * @param array $overrideData
     *
     * @return \Generated\Shared\Transfer\CalculableObjectTransfer
     */
    private function buildCalculableObjectTransfer(array $overrideData): CalculableObjectTransfer
    {
        return (new CalculableObjectBuilder($overrideData))->build();
    }

    /**
     * @return \Pyz\Zed\MyWorldPayment\Business\Calculator\BenefitVoucherPaymentCalculator
     */
    private function createBenefitVoucherPaymentCalculator(): BenefitVoucherPaymentCalculator
    {
        /** @var \Pyz\Zed\MyWorldPayment\Business\MyWorldPaymentBusinessFactory $myWorldBusinessFactory */
        $myWorldBusinessFactory = $this->tester->getFactory('MyWorldPayment');

        return new BenefitVoucherPaymentCalculator(
            $this->tester->getConfig(),
            $myWorldBusinessFactory->createItemTransferDealsChecker()
        );
    }
}
