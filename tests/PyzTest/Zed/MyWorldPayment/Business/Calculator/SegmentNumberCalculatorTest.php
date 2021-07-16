<?php
declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Zed\MyWorldPayment\Business\Calculator;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\CalculableObjectBuilder;
use Generated\Shared\Transfer\CalculableObjectTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Pyz\Zed\MyWorldPayment\Business\Calculator\SegmentNumber\SegmentNumberNewCalculator;
use Pyz\Zed\MyWorldPayment\Business\Calculator\SegmentNumber\SegmentNumberOneSenseCalculator;
use Pyz\Zed\MyWorldPayment\Business\Calculator\SegmentNumber\SegmentNumberZeroMarginCalculator;
use Pyz\Zed\MyWorldPayment\Business\Calculator\SegmentNumberCalculator;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Zed
 * @group MyWorldPayment
 * @group Business
 * @group Calculator
 * @group SegmentNumberCalculatorTest
 * Add your own group annotations below this line
 */
class SegmentNumberCalculatorTest extends Unit
{
    /**
     * @var \PyzTest\Zed\MyWorldPayment\MyWorldPaymentBusinessTester
     */
    protected $tester;

    /**
     * @var \Pyz\Zed\MyWorldPayment\Business\Calculator\SegmentNumberCalculator
     */
    private $sut;

    /**
     * @return void
     */
    protected function _before(): void
    {
        $this->sut = new SegmentNumberCalculator();
    }

    /**
     * @dataProvider dataProviderQuote
     *
     * @param array $calculableObjectData
     * @param array $expectedSegmentNumbers
     *
     * @return void
     */
    public function testIfSegmentNumberIsCorrectAfterQuoteRecalculation(
        array $calculableObjectData,
        array $expectedSegmentNumbers
    ): void {
        $calculableObject = (new CalculableObjectBuilder($calculableObjectData))->build();
        $this->sut->recalculateQuote($calculableObject);

        foreach ($calculableObject->getItems() as $key => $item) {
            $this->assertSame(
                $expectedSegmentNumbers[$key],
                $item->getSegmentNumber()
            );
        }
    }

    /**
     * @return array
     */
    public function dataProviderQuote(): array
    {
        return [
            'single item with benefit voucher' => [
                'calculableObjectData' => [
                    CalculableObjectTransfer::ITEMS => [
                        [
                            ItemTransfer::QUANTITY => 1,
                            ItemTransfer::USE_BENEFIT_VOUCHER => true,
                            ItemTransfer::TOTAL_USED_BENEFIT_VOUCHERS_AMOUNT => 1000,
                        ],
                    ],
                ],
                'expectedSegmentNumbers' => [
                    SegmentNumberZeroMarginCalculator::SEGMENT_NUMBER_ZERO_MARGIN,
                ],
            ],
            'single item with shopping point' => [
                'calculableObjectData' => [
                    CalculableObjectTransfer::ITEMS => [
                        [
                            ItemTransfer::QUANTITY => 1,
                            ItemTransfer::USE_SHOPPING_POINTS => true,
                            ItemTransfer::TOTAL_USED_SHOPPING_POINTS_AMOUNT => 10,
                        ],
                    ],
                ],
                'expectedSegmentNumbers' => [
                    SegmentNumberZeroMarginCalculator::SEGMENT_NUMBER_ZERO_MARGIN,
                ],
            ],
            'two items with shopping point and benefit voucher' => [
                'calculableObjectData' => [
                    CalculableObjectTransfer::ITEMS => [
                        [
                            ItemTransfer::QUANTITY => 1,
                            ItemTransfer::USE_SHOPPING_POINTS => true,
                            ItemTransfer::TOTAL_USED_SHOPPING_POINTS_AMOUNT => 10,
                        ],
                        [
                            ItemTransfer::QUANTITY => 1,
                            ItemTransfer::USE_SHOPPING_POINTS => true,
                            ItemTransfer::TOTAL_USED_SHOPPING_POINTS_AMOUNT => 10,
                        ],
                    ],
                ],
                'expectedSegmentNumbers' => [
                    SegmentNumberZeroMarginCalculator::SEGMENT_NUMBER_ZERO_MARGIN,
                    SegmentNumberZeroMarginCalculator::SEGMENT_NUMBER_ZERO_MARGIN,
                ],
            ],
            'two items with shopping point and nothing' => [
                'calculableObjectData' => [
                    CalculableObjectTransfer::ITEMS => [
                        [
                            ItemTransfer::QUANTITY => 1,
                            ItemTransfer::USE_SHOPPING_POINTS => true,
                            ItemTransfer::TOTAL_USED_SHOPPING_POINTS_AMOUNT => 10,
                        ],
                        [
                            ItemTransfer::QUANTITY => 1,
                        ],
                    ],
                ],
                'expectedSegmentNumbers' => [
                    SegmentNumberZeroMarginCalculator::SEGMENT_NUMBER_ZERO_MARGIN,
                    SegmentNumberNewCalculator::SEGMENT_NUMBER_NEW,
                ],
            ],
            'three items with shopping point, nothing and onesense' => [
                'calculableObjectData' => [
                    CalculableObjectTransfer::ITEMS => [
                        [
                            ItemTransfer::QUANTITY => 1,
                            ItemTransfer::USE_SHOPPING_POINTS => true,
                            ItemTransfer::TOTAL_USED_SHOPPING_POINTS_AMOUNT => 10,
                        ],
                        [
                            ItemTransfer::QUANTITY => 1,
                        ],
                        [
                            ItemTransfer::QUANTITY => 1,
                            ItemTransfer::CONCRETE_ATTRIBUTES => [
                                'brand' => 'ONESENSE',
                            ],
                        ],
                    ],
                ],
                'expectedSegmentNumbers' => [
                    SegmentNumberZeroMarginCalculator::SEGMENT_NUMBER_ZERO_MARGIN,
                    SegmentNumberNewCalculator::SEGMENT_NUMBER_NEW,
                    SegmentNumberOneSenseCalculator::SEGMENT_NUMBER_ONESENSE_PRODUCTS,
                ],
            ],
        ];
    }
}
