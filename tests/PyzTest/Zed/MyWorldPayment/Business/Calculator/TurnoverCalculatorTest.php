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
use Pyz\Zed\MyWorldPayment\Business\Calculator\TurnoverCalculator;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Zed
 * @group MyWorldPayment
 * @group Business
 * @group Calculator
 * @group TurnoverCalculatorTest
 * Add your own group annotations below this line
 */
class TurnoverCalculatorTest extends Unit
{
    /**
     * @var \PyzTest\Zed\MyWorldPayment\MyWorldPaymentBusinessTester
     */
    protected $tester;

    /**
     * @var \Pyz\Zed\MyWorldPayment\Business\Calculator\TurnoverCalculator
     */
    private $sut;

    /**
     * @return void
     */
    protected function _before(): void
    {
        $this->sut = new TurnoverCalculator();
    }

    /**
     * @dataProvider dataProviderQuote
     *
     * @param array $calculableObjectData
     * @param array $expectedTurnoverAmounts
     *
     * @return void
     */
    public function testIfSegmentNumberIsCorrectAfterQuoteRecalculation(
        array $calculableObjectData,
        array $expectedTurnoverAmounts
    ): void {
        $calculableObject = (new CalculableObjectBuilder($calculableObjectData))->build();
        $this->sut->recalculateQuote($calculableObject);

        foreach ($calculableObject->getItems() as $key => $item) {
            $this->assertSame(
                $expectedTurnoverAmounts[$key],
                $item->getTurnoverAmount()
            );
        }
    }

    /**
     * @return array
     */
    public function dataProviderQuote(): array
    {
        return [
            'single item with benefit price' => [
                'calculableObjectData' => [
                    CalculableObjectTransfer::ITEMS => [
                        [
                            ItemTransfer::SUM_BENEFIT_PRICE => 1000,
                            ItemTransfer::SUM_PRICE => 2000,
                        ],
                    ],
                ],
                'expectedTurnoverAmounts' => [
                    1000,
                ],
            ],
            'two items with benefit price' => [
                'calculableObjectData' => [
                    CalculableObjectTransfer::ITEMS => [
                        [
                            ItemTransfer::SUM_BENEFIT_PRICE => 1000,
                            ItemTransfer::SUM_PRICE => 2000,
                        ],
                        [
                            ItemTransfer::SUM_BENEFIT_PRICE => 5000,
                            ItemTransfer::SUM_PRICE => 10000,
                        ],
                    ],
                ],
                'expectedTurnoverAmounts' => [
                    1000, 5000,
                ],
            ],
            'two items with benefit price and sum price' => [
                'calculableObjectData' => [
                    CalculableObjectTransfer::ITEMS => [
                        [
                            ItemTransfer::SUM_BENEFIT_PRICE => 1000,
                            ItemTransfer::SUM_PRICE => 2000,
                        ],
                        [
                            ItemTransfer::SUM_PRICE => 10000,
                        ],
                    ],
                ],
                'expectedTurnoverAmounts' => [
                    1000, 10000,
                ],
            ],
        ];
    }
}
