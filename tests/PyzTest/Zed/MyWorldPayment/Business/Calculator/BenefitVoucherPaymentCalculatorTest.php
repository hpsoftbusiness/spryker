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
use Pyz\Service\Customer\CustomerServiceInterface;
use Pyz\Zed\MyWorldPayment\Business\Calculator\BenefitVoucherPaymentCalculator;

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
     * @param array $itemData
     * @param array $itemExpectedTotalUsedBenefitVoucherAmount
     *
     * @return void
     */
    public function testBenefitVoucherAmountSplitByQuantity(
        array $itemData,
        array $itemExpectedTotalUsedBenefitVoucherAmount
    ): void {
        $calculableObjectTransfer = $this->buildCalculableObjectTransfer($itemData);

        $calculableObjectTransfer = $this->sut->recalculateOrder($calculableObjectTransfer);

        foreach ($calculableObjectTransfer->getItems() as $index => $itemTransfer) {
            self::assertEquals(
                $itemExpectedTotalUsedBenefitVoucherAmount[$index],
                $itemTransfer->getTotalUsedBenefitVouchersAmount()
            );
        }
    }

    /**
     * @return array
     */
    public function provideItemData(): array
    {
        return [
            'single item with Benefit Voucher' => [
                'items' => [
                    [
                        ItemTransfer::QUANTITY => 1,
                        ItemTransfer::USE_BENEFIT_VOUCHER => true,
                        ItemTransfer::BENEFIT_VOUCHER_DEAL_DATA => [
                            BenefitVoucherDealDataTransfer::AMOUNT => 200,
                            BenefitVoucherDealDataTransfer::SALES_PRICE => 800,
                            BenefitVoucherDealDataTransfer::IS_STORE => true,
                        ],
                        ItemTransfer::TOTAL_USED_BENEFIT_VOUCHERS_AMOUNT => 200,
                    ],
                ],
                'expectedAmount' => [
                    200,
                ],
            ],
            'single item with higher quantity and Benefit Voucher' => [
                'items' => [
                    [
                        ItemTransfer::QUANTITY => 1,
                        ItemTransfer::USE_BENEFIT_VOUCHER => true,
                        ItemTransfer::BENEFIT_VOUCHER_DEAL_DATA => [
                            BenefitVoucherDealDataTransfer::AMOUNT => 200,
                            BenefitVoucherDealDataTransfer::SALES_PRICE => 800,
                            BenefitVoucherDealDataTransfer::IS_STORE => true,
                        ],
                        ItemTransfer::TOTAL_USED_BENEFIT_VOUCHERS_AMOUNT => 400,
                    ],
                    [
                        ItemTransfer::QUANTITY => 1,
                        ItemTransfer::USE_BENEFIT_VOUCHER => true,
                        ItemTransfer::BENEFIT_VOUCHER_DEAL_DATA => [
                            BenefitVoucherDealDataTransfer::AMOUNT => 200,
                            BenefitVoucherDealDataTransfer::SALES_PRICE => 800,
                            BenefitVoucherDealDataTransfer::IS_STORE => true,
                        ],
                        ItemTransfer::TOTAL_USED_BENEFIT_VOUCHERS_AMOUNT => 400,
                    ],
                ],
                'expectedAmount' => [
                    200,
                    200,
                ],
            ],
            'multiple items with Benefit Voucher' => [
                'items' => [
                    [
                        ItemTransfer::QUANTITY => 1,
                        ItemTransfer::USE_BENEFIT_VOUCHER => true,
                        ItemTransfer::BENEFIT_VOUCHER_DEAL_DATA => [
                            BenefitVoucherDealDataTransfer::AMOUNT => 200,
                            BenefitVoucherDealDataTransfer::SALES_PRICE => 800,
                            BenefitVoucherDealDataTransfer::IS_STORE => true,
                        ],
                        ItemTransfer::TOTAL_USED_BENEFIT_VOUCHERS_AMOUNT => 400,
                    ],
                    [
                        ItemTransfer::QUANTITY => 1,
                        ItemTransfer::USE_BENEFIT_VOUCHER => true,
                        ItemTransfer::BENEFIT_VOUCHER_DEAL_DATA => [
                            BenefitVoucherDealDataTransfer::AMOUNT => 200,
                            BenefitVoucherDealDataTransfer::SALES_PRICE => 800,
                            BenefitVoucherDealDataTransfer::IS_STORE => true,
                        ],
                        ItemTransfer::TOTAL_USED_BENEFIT_VOUCHERS_AMOUNT => 400,

                    ],
                    [
                        ItemTransfer::QUANTITY => 1,
                        ItemTransfer::USE_BENEFIT_VOUCHER => false,
                    ],
                    [
                        ItemTransfer::QUANTITY => 1,
                        ItemTransfer::USE_BENEFIT_VOUCHER => true,
                        ItemTransfer::BENEFIT_VOUCHER_DEAL_DATA => [
                            BenefitVoucherDealDataTransfer::AMOUNT => 100,
                            BenefitVoucherDealDataTransfer::SALES_PRICE => 800,
                            BenefitVoucherDealDataTransfer::IS_STORE => true,
                        ],
                        ItemTransfer::TOTAL_USED_BENEFIT_VOUCHERS_AMOUNT => 200,
                    ],
                    [
                        ItemTransfer::QUANTITY => 1,
                        ItemTransfer::USE_BENEFIT_VOUCHER => true,
                        ItemTransfer::BENEFIT_VOUCHER_DEAL_DATA => [
                            BenefitVoucherDealDataTransfer::AMOUNT => 100,
                            BenefitVoucherDealDataTransfer::SALES_PRICE => 800,
                            BenefitVoucherDealDataTransfer::IS_STORE => true,
                        ],
                        ItemTransfer::TOTAL_USED_BENEFIT_VOUCHERS_AMOUNT => 200,
                    ],
                ],
                'expectedAmount' => [
                    200,
                    200,
                    null,
                    100,
                    100,
                ],
            ],
            'item missing Benefit Voucher data' => [
                'items' => [
                    [
                        ItemTransfer::QUANTITY => 1,
                        ItemTransfer::USE_BENEFIT_VOUCHER => true,
                    ],
                ],
                'expectedAmount' => [
                    null,
                ],
            ],
            'item not flagged for Benefit Voucher does not get processed' => [
                'items' => [
                    [
                        ItemTransfer::QUANTITY => 1,
                        ItemTransfer::USE_BENEFIT_VOUCHER => false,
                        ItemTransfer::BENEFIT_VOUCHER_DEAL_DATA => [
                            BenefitVoucherDealDataTransfer::AMOUNT => 100,
                            BenefitVoucherDealDataTransfer::SALES_PRICE => 800,
                            BenefitVoucherDealDataTransfer::IS_STORE => true,
                        ],
                        ItemTransfer::TOTAL_USED_BENEFIT_VOUCHERS_AMOUNT => 200,
                    ],
                ],
                'expectedAmount' => [
                    200,
                ],
            ],
        ];
    }

    /**
     * @param array $itemData
     *
     * @return \Generated\Shared\Transfer\CalculableObjectTransfer
     */
    private function buildCalculableObjectTransfer(array $itemData): CalculableObjectTransfer
    {
        return (new CalculableObjectBuilder([
            CalculableObjectTransfer::ITEMS => $itemData,
        ]))->build();
    }

    /**
     * @return \Pyz\Zed\MyWorldPayment\Business\Calculator\BenefitVoucherPaymentCalculator
     */
    private function createBenefitVoucherPaymentCalculator(): BenefitVoucherPaymentCalculator
    {
        return new BenefitVoucherPaymentCalculator(
            $this->tester->getConfig(),
            $this->mockCustomerService()
        );
    }

    /**
     * @return \Pyz\Service\Customer\CustomerServiceInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private function mockCustomerService(): CustomerServiceInterface
    {
        return $this->createMock(CustomerServiceInterface::class);
    }
}
