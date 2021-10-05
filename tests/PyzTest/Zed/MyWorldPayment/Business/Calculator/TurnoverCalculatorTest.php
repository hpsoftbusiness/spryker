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
use Generated\Shared\Transfer\ShoppingPointsDealTransfer;
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
        /** @var \Pyz\Zed\MyWorldPayment\Business\MyWorldPaymentBusinessFactory $myWorldBusinessFactory */
        $myWorldBusinessFactory = $this->tester->getFactory('MyWorldPayment');
        $this->sut = new TurnoverCalculator($myWorldBusinessFactory->createItemTransferDealsChecker());
    }

    /**
     * @dataProvider dataProvider
     *
     * @param array $item
     * @param int $expectedTurnoverAmount
     *
     * @return void
     */
    public function testTurnoverAmount(array $item, int $expectedTurnoverAmount): void
    {
        /** @var \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObject */
        $calculableObject = (new CalculableObjectBuilder([
            CalculableObjectTransfer::ITEMS => [$item],
        ]))->build();

        $this->sut->recalculateQuote($calculableObject);

        $this->assertSame($expectedTurnoverAmount, $calculableObject->getItems()[0]->getTurnoverAmount());
    }

    /**
     * @return array
     */
    public function dataProvider(): array
    {
        return [
            'not SP item' => [
                'item' => [
                    ItemTransfer::SUM_PRICE_TO_PAY_AGGREGATION => 1000,
                ],
                'expectedTurnoverAmount' => 1000,
            ],
            'SP item' => [
                'item' => [
                    ItemTransfer::SUM_PRICE_TO_PAY_AGGREGATION => 1000,
                    ItemTransfer::UNIT_PRICE => 1500,
                    ItemTransfer::QUANTITY => 1,
                    ItemTransfer::SHOPPING_POINTS_DEAL => [
                        ShoppingPointsDealTransfer::PRICE => 1000,
                        ShoppingPointsDealTransfer::IS_ACTIVE => true,
                        ShoppingPointsDealTransfer::SHOPPING_POINTS_QUANTITY => 1,
                    ],
                ],
                'expectedTurnoverAmount' => 1500,
            ],
            'multiple SP item' => [
                'item' => [
                    ItemTransfer::SUM_PRICE_TO_PAY_AGGREGATION => 1000,
                    ItemTransfer::UNIT_PRICE => 600,
                    ItemTransfer::QUANTITY => 2,
                    ItemTransfer::SHOPPING_POINTS_DEAL => [
                        ShoppingPointsDealTransfer::PRICE => 500,
                        ShoppingPointsDealTransfer::IS_ACTIVE => true,
                        ShoppingPointsDealTransfer::SHOPPING_POINTS_QUANTITY => 1,
                    ],
                ],
                'expectedTurnoverAmount' => 1200,
            ],
        ];
    }
}
