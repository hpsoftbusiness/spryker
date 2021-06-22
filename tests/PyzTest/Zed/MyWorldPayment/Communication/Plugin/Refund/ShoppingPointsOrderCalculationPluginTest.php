<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Zed\MyWorldPayment\Communication\Plugin\Refund;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CalculableObjectTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ShoppingPointsDealTransfer;
use Pyz\Zed\MyWorldPayment\Communication\Plugin\Calculation\ShoppingPointsOrderCalculationPlugin;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Zed
 * @group MyWorldPayment
 * @group Communication
 * @group Plugin
 * @group Refund
 * @group ShoppingPointsOrderCalculationPluginTest
 * Add your own group annotations below this line
 */
class ShoppingPointsOrderCalculationPluginTest extends Unit
{
    /**
     * @var \PyzTest\Zed\MyWorldPayment\MyWorldPaymentCommunicationTester
     */
    protected $tester;

    /**
     * @var \Pyz\Zed\MyWorldPayment\Communication\Plugin\Calculation\ShoppingPointsOrderCalculationPlugin
     */
    private $sut;

    /**
     * @return void
     */
    protected function _before(): void
    {
        $this->sut = $this->createShoppingPointsOrderCalculationPlugin();
    }

    /**
     * @return void
     */
    public function testItemUsedShoppingAmountSplitByItemQuantity(): void
    {
        $calculableObjectTransfer = $this->tester->buildCalculableObjectTransfer([
            CalculableObjectTransfer::ITEMS => [
                [
                    ItemTransfer::USE_SHOPPING_POINTS => true,
                    ItemTransfer::QUANTITY => 1,
                    ItemTransfer::TOTAL_USED_SHOPPING_POINTS_AMOUNT => 10,
                    ItemTransfer::SHOPPING_POINTS_DEAL => [
                        ShoppingPointsDealTransfer::IS_ACTIVE => true,
                        ShoppingPointsDealTransfer::SHOPPING_POINTS_QUANTITY => 5,
                        ShoppingPointsDealTransfer::PRICE => 800,
                    ],
                ],
                [
                    ItemTransfer::USE_SHOPPING_POINTS => true,
                    ItemTransfer::QUANTITY => 1,
                    ItemTransfer::TOTAL_USED_SHOPPING_POINTS_AMOUNT => 10,
                    ItemTransfer::SHOPPING_POINTS_DEAL => [
                        ShoppingPointsDealTransfer::IS_ACTIVE => true,
                        ShoppingPointsDealTransfer::SHOPPING_POINTS_QUANTITY => 5,
                        ShoppingPointsDealTransfer::PRICE => 800,
                    ],
                ],
                [
                    ItemTransfer::QUANTITY => 1,
                ],
                [
                    ItemTransfer::QUANTITY => 1,
                    ItemTransfer::USE_SHOPPING_POINTS => true,
                ],
                [
                    ItemTransfer::QUANTITY => 1,
                    ItemTransfer::USE_SHOPPING_POINTS => true,
                    ItemTransfer::SHOPPING_POINTS_DEAL => [
                        ShoppingPointsDealTransfer::IS_ACTIVE => false,
                        ShoppingPointsDealTransfer::SHOPPING_POINTS_QUANTITY => 5,
                    ],
                ],
                [
                    ItemTransfer::USE_SHOPPING_POINTS => true,
                    ItemTransfer::QUANTITY => 3,
                    ItemTransfer::IS_QUANTITY_SPLITTABLE => false,
                    ItemTransfer::TOTAL_USED_SHOPPING_POINTS_AMOUNT => 15,
                    ItemTransfer::SHOPPING_POINTS_DEAL => [
                        ShoppingPointsDealTransfer::IS_ACTIVE => true,
                        ShoppingPointsDealTransfer::SHOPPING_POINTS_QUANTITY => 5,
                        ShoppingPointsDealTransfer::PRICE => 800,
                    ],
                ],
            ],
        ]);

        $this->sut->recalculate($calculableObjectTransfer);

        self::assertEquals(5, $calculableObjectTransfer->getItems()[0]->getTotalUsedShoppingPointsAmount());
        self::assertEquals(5, $calculableObjectTransfer->getItems()[1]->getTotalUsedShoppingPointsAmount());
        self::assertNull($calculableObjectTransfer->getItems()[2]->getTotalUsedShoppingPointsAmount());
        self::assertNull($calculableObjectTransfer->getItems()[3]->getTotalUsedShoppingPointsAmount());
        self::assertNull($calculableObjectTransfer->getItems()[4]->getTotalUsedShoppingPointsAmount());
        self::assertEquals(15, $calculableObjectTransfer->getItems()[5]->getTotalUsedShoppingPointsAmount());
    }

    /**
     * @return \Pyz\Zed\MyWorldPayment\Communication\Plugin\Calculation\ShoppingPointsOrderCalculationPlugin
     */
    private function createShoppingPointsOrderCalculationPlugin(): ShoppingPointsOrderCalculationPlugin
    {
        return new ShoppingPointsOrderCalculationPlugin();
    }
}
