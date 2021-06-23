<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Zed\Discount\Communication\Plugin\Calculator;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DiscountableItemTransfer;
use Pyz\Zed\Discount\Communication\Plugin\Calculator\BenefitPriceDiscountCalculator;
use PyzTest\Zed\Discount\DataHelper;
use Spryker\Zed\Discount\Dependency\Plugin\DiscountCalculatorPluginInterface;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Zed
 * @group Discount
 * @group Communication
 * @group Plugin
 * @group Calculator
 * @group BenefitPriceDiscountCalculatorTest
 * Add your own group annotations below this line
 */
class BenefitPriceDiscountCalculatorTest extends Unit
{
    /**
     * @var \PyzTest\Zed\Discount\DiscountCommunicationTester
     */
    protected $tester;

    /**
     * @var \Spryker\Zed\Discount\Dependency\Plugin\DiscountCalculatorPluginInterface
     */
    private $sut;

    /**
     * @return void
     */
    protected function _before(): void
    {
        $this->sut = $this->createBenefitPriceDiscountCalculator();
    }

    /**
     * @return void
     */
    public function testCalculateDiscount(): void
    {
        $discountableItemTransfers = [
            $this->tester->buildDiscountableItemTransfer([DiscountableItemTransfer::ORIGINAL_ITEM => DataHelper::ITEM_DATA_SP]),
            $this->tester->buildDiscountableItemTransfer([DiscountableItemTransfer::ORIGINAL_ITEM => DataHelper::ITEM_DATA_SP_2]),
        ];
        $discountTransfer = $this->tester->buildDiscountTransfer();

        $discountAmount = $this->sut->calculateDiscount($discountableItemTransfers, $discountTransfer);

        self::assertEquals(800, $discountAmount);
    }

    /**
     * @return \Spryker\Zed\Discount\Dependency\Plugin\DiscountCalculatorPluginInterface
     */
    private function createBenefitPriceDiscountCalculator(): DiscountCalculatorPluginInterface
    {
        return new BenefitPriceDiscountCalculator();
    }
}
