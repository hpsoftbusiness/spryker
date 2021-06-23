<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Zed\Discount\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ClauseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Pyz\Shared\Discount\DiscountConstants;
use Pyz\Zed\Discount\Business\DiscountFacadeInterface;
use Pyz\Zed\Discount\Dependency\Plugin\InternalDiscountCollectorPluginInterface;
use Pyz\Zed\Discount\DiscountDependencyProvider;
use PyzTest\Zed\Discount\DataHelper;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Zed
 * @group Discount
 * @group Business
 * @group Facade
 * @group DiscountFacadeTest
 * Add your own group annotations below this line
 */
class DiscountFacadeTest extends Unit
{
    /**
     * @var \PyzTest\Zed\Discount\DiscountBusinessTester
     */
    protected $tester;

    /**
     * @var \Pyz\Zed\Discount\Business\DiscountFacadeInterface
     */
    private $sut;

    /**
     * @return void
     */
    protected function _before(): void
    {
        $this->sut = $this->getFacade();
    }

    /**
     * @return void
     */
    public function testCollectByUseShoppingPoints(): void
    {
        $quoteTransfer = $this->tester->buildQuoteTransfer([
            QuoteTransfer::ITEMS => [
                DataHelper::ITEM_DATA_DEFAULT,
                DataHelper::ITEM_DATA_SP,
                DataHelper::ITEM_DATA_WITH_MISSING_SP_DEAL,
            ],
        ]);

        $discountableItemTransfers = $this->sut->collectByUseShoppingPoints($quoteTransfer, new ClauseTransfer());

        self::assertCount(1, $discountableItemTransfers);
        self::assertTrue($discountableItemTransfers[0]->getOriginalItem()->getUseShoppingPoints());
    }

    /**
     * @return void
     */
    public function testCollectForInternalDiscount(): void
    {
        $internalDiscountCollectorPluginMock = $this->mockInternalDiscountCollectorPlugin();
        $internalDiscountCollectorPluginMock
            ->expects(self::once())
            ->method('getName')
            ->willReturn(DataHelper::INTERNAL_DISCOUNT_COLLECTOR_PLUGIN_NAME);

        $internalDiscountCollectorPluginMock
            ->expects(self::once())
            ->method('collect');

        $this->tester->setDependency(
            DiscountDependencyProvider::PLUGIN_INTERNAL_DISCOUNT_COLLECTOR,
            [
                $internalDiscountCollectorPluginMock,
            ]
        );

        $discountTransfer = $this->tester->buildDiscountTransfer(DataHelper::DISCOUNT_DATA);
        $quoteTransfer = $this->tester->buildQuoteTransfer();

        $this->sut->collectForInternalDiscount($discountTransfer, $quoteTransfer);
    }

    /**
     * @return void
     */
    public function testCalculateDiscountForShoppingPoints(): void
    {
        $quoteTransfer = $this->tester->buildQuoteTransfer([
            QuoteTransfer::ITEMS => [
                DataHelper::ITEM_DATA_SP,
                DataHelper::ITEM_DATA_DEFAULT,
                DataHelper::ITEM_DATA_SP_2,
            ],
        ]);

        $quoteTransfer = $this->sut->calculateDiscounts($quoteTransfer);

        self::assertCount(1, $quoteTransfer->getCartRuleDiscounts());
        $discount = $quoteTransfer->getCartRuleDiscounts()[0];
        self::assertEquals(DiscountConstants::TYPE_INTERNAL_DISCOUNT, $discount->getDiscountType());
        self::assertEquals(800, $discount->getAmount());
    }

    /**
     * @return void
     */
    public function testCalculateDiscountForShoppingPointsNotApplied(): void
    {
        $quoteTransfer = $this->tester->buildQuoteTransfer([
            QuoteTransfer::ITEMS => [
                DataHelper::ITEM_DATA_DEFAULT,
                DataHelper::ITEM_DATA_WITH_MISSING_SP_DEAL,
            ],
        ]);

        $quoteTransfer = $this->sut->calculateDiscounts($quoteTransfer);

        self::assertCount(0, $quoteTransfer->getCartRuleDiscounts());
    }

    /**
     * @return \Pyz\Zed\Discount\Business\DiscountFacadeInterface
     */
    private function getFacade(): DiscountFacadeInterface
    {
        return $this->tester->getLocator()->discount()->facade();
    }

    /**
     * @return \Pyz\Zed\Discount\Dependency\Plugin\InternalDiscountCollectorPluginInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private function mockInternalDiscountCollectorPlugin(): InternalDiscountCollectorPluginInterface
    {
        return $this->createMock(InternalDiscountCollectorPluginInterface::class);
    }
}
