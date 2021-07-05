<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Yves\CheckoutPage\Process\Steps;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\BenefitVoucherDealDataTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ShoppingPointsDealTransfer;
use Pyz\Yves\CheckoutPage\Process\Steps\BenefitDealStep;
use Pyz\Yves\CheckoutPage\Process\Steps\BreadcrumbChecker\BreadcrumbStatusChecker;
use Pyz\Yves\CheckoutPage\Process\Steps\BreadcrumbChecker\BreadcrumbStatusCheckerInterface;
use SprykerShop\Yves\CheckoutPage\Process\Steps\PostConditionCheckerInterface;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Yves
 * @group CheckoutPage
 * @group Process
 * @group Steps
 * @group BenefitDealStepTest
 * Add your own group annotations below this line
 */
class BenefitDealStepTest extends Unit
{
    private const STEP_ROUTE = 'benefit-step-test-route';
    private const STEP_ESCAPE_ROUTE = 'benefit-step-test-escape-route';

    /**
     * @var \PyzTest\Yves\CheckoutPage\CheckoutPageProcessTester
     */
    protected $tester;

    /**
     * @var \Pyz\Yves\CheckoutPage\Process\Steps\BenefitDealStep
     */
    private $sut;

    /**
     * @var \SprykerShop\Yves\CheckoutPage\Process\Steps\PostConditionCheckerInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private $addressesPostConditionCheckMock;

    /**
     * @var \SprykerShop\Yves\CheckoutPage\Process\Steps\PostConditionCheckerInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private $shipmentPostConditionCheckMock;

    /**
     * @return void
     */
    protected function _before(): void
    {
        $this->addressesPostConditionCheckMock = $this->mockPostConditionChecker();
        $this->shipmentPostConditionCheckMock = $this->mockPostConditionChecker();
        $this->sut = $this->createBenefitDealStep();
    }

    /**
     * @return void
     */
    public function testStepRequiresInputIfQuoteItemsHaveShoppingPointsStoreEnabled(): void
    {
        $quoteTransfer = $this->tester->buildQuoteTransfer([], [
            [
                ItemTransfer::SHOPPING_POINTS_DEAL => [
                    ShoppingPointsDealTransfer::IS_ACTIVE => true,
                    ShoppingPointsDealTransfer::PRICE => 1,
                ],
            ],
        ]);

        self::assertTrue(
            $this->sut->requireInput($quoteTransfer)
        );
    }

    /**
     * @return void
     */
    public function testStepRequiresInputIfQuoteItemsHaveBenefitStoreEnabled(): void
    {
        $quoteTransfer = $this->tester->buildQuoteTransfer([], [
            [
                ItemTransfer::BENEFIT_VOUCHER_DEAL_DATA => [
                    BenefitVoucherDealDataTransfer::IS_STORE => true,
                    BenefitVoucherDealDataTransfer::SALES_PRICE => 1,
                ],
            ],
        ]);

        self::assertTrue(
            $this->sut->requireInput($quoteTransfer)
        );
    }

    /**
     * @return void
     */
    public function testStepRequiresInputIfQuoteItemsHaveBenefitStoreAndShoppingPointStoreEnabled(): void
    {
        $quoteTransfer = $this->tester->buildQuoteTransfer([], [
            [
                ItemTransfer::BENEFIT_VOUCHER_DEAL_DATA => [
                    BenefitVoucherDealDataTransfer::IS_STORE => true,
                    BenefitVoucherDealDataTransfer::SALES_PRICE => 1,
                ],
            ],
            [
                ItemTransfer::SHOPPING_POINTS_DEAL => [
                    ShoppingPointsDealTransfer::IS_ACTIVE => true,
                    ShoppingPointsDealTransfer::PRICE => 1,
                ],
            ],
        ]);

        self::assertTrue(
            $this->sut->requireInput($quoteTransfer)
        );
    }

    /**
     * @return void
     */
    public function testStepNotRequiresInputIfQuoteItemsDontHaveBenefitDealStoresEnabled(): void
    {
        $quoteTransfer = $this->tester->buildQuoteTransfer([], [
            [
                ItemTransfer::BENEFIT_VOUCHER_DEAL_DATA => [
                    BenefitVoucherDealDataTransfer::IS_STORE => false,
                ],
            ],
            [
                ItemTransfer::SHOPPING_POINTS_DEAL => [
                    ShoppingPointsDealTransfer::IS_ACTIVE => false,
                ],
            ],
        ]);

        self::assertFalse(
            $this->sut->requireInput($quoteTransfer)
        );
    }

    /**
     * @return void
     */
    public function testStepNotRequiresInputIfQuoteItemsMissingBenefitDealsData(): void
    {
        $quoteTransfer = $this->tester->buildQuoteTransfer();

        self::assertFalse(
            $this->sut->requireInput($quoteTransfer)
        );
    }

    /**
     * @return void
     */
    public function testStepBreadcrumbIsEnabledIfAddressesAndShipmentAreSet(): void
    {
        $quoteTransfer = $this->tester->buildQuoteTransfer();
        $this->addressesPostConditionCheckMock
            ->method('check')
            ->willReturn(true);

        $this->shipmentPostConditionCheckMock
            ->method('check')
            ->willReturn(true);

        self::assertTrue(
            $this->sut->isBreadcrumbItemEnabled($quoteTransfer)
        );
    }

    /**
     * @return void
     */
    public function testStepBreadcrumbIsDisabledIfAddressesDataMissing(): void
    {
        $quoteTransfer = $this->tester->buildQuoteTransfer();
        $this->addressesPostConditionCheckMock
            ->method('check')
            ->willReturn(false);

        $this->shipmentPostConditionCheckMock
            ->method('check')
            ->willReturn(true);

        self::assertFalse(
            $this->sut->isBreadcrumbItemEnabled($quoteTransfer)
        );
    }

    /**
     * @return void
     */
    public function testStepBreadcrumbIsDisabledIfShipmentDataMissing(): void
    {
        $quoteTransfer = $this->tester->buildQuoteTransfer();
        $this->addressesPostConditionCheckMock
            ->method('check')
            ->willReturn(true);

        $this->shipmentPostConditionCheckMock
            ->method('check')
            ->willReturn(false);

        self::assertFalse(
            $this->sut->isBreadcrumbItemEnabled($quoteTransfer)
        );
    }

    /**
     * @return void
     */
    public function testStepBreadcrumbVisibleIfQuoteItemsHaveApplicableBenefitDeals(): void
    {
        $quoteTransfer = $this->tester->buildQuoteTransfer([], [
            [
                ItemTransfer::BENEFIT_VOUCHER_DEAL_DATA => [
                    BenefitVoucherDealDataTransfer::IS_STORE => false,
                ],
            ],
            [
                ItemTransfer::SHOPPING_POINTS_DEAL => [
                    ShoppingPointsDealTransfer::IS_ACTIVE => true,
                    ShoppingPointsDealTransfer::PRICE => 1,
                ],
            ],
        ]);

        self::assertFalse(
            $this->sut->isBreadcrumbItemHidden($quoteTransfer)
        );
    }

    /**
     * @return void
     */
    public function testStepBreadcrumbHiddenIfQuoteItemsNotHaveApplicableBenefitDeals(): void
    {
        $quoteTransfer = $this->tester->buildQuoteTransfer([], [
            [
                ItemTransfer::BENEFIT_VOUCHER_DEAL_DATA => [
                    BenefitVoucherDealDataTransfer::IS_STORE => false,
                ],
            ],
            [
                ItemTransfer::SHOPPING_POINTS_DEAL => [
                    ShoppingPointsDealTransfer::IS_ACTIVE => false,
                ],
            ],
        ]);

        self::assertTrue(
            $this->sut->isBreadcrumbItemHidden($quoteTransfer)
        );
    }

    /**
     * @return \Pyz\Yves\CheckoutPage\Process\Steps\BenefitDealStep
     */
    private function createBenefitDealStep(): BenefitDealStep
    {
        return new BenefitDealStep(
            $this->tester->getLocator()->customer()->service(),
            $this->createBreadcrumbStatusChecker(),
            self::STEP_ROUTE,
            self::STEP_ESCAPE_ROUTE
        );
    }

    /**
     * @return \Pyz\Yves\CheckoutPage\Process\Steps\BreadcrumbChecker\BreadcrumbStatusCheckerInterface
     */
    private function createBreadcrumbStatusChecker(): BreadcrumbStatusCheckerInterface
    {
        return new BreadcrumbStatusChecker([
            $this->addressesPostConditionCheckMock,
            $this->shipmentPostConditionCheckMock,
        ]);
    }

    /**
     * @return \SprykerShop\Yves\CheckoutPage\Process\Steps\PostConditionCheckerInterface
     */
    private function mockPostConditionChecker(): PostConditionCheckerInterface
    {
        return $this->createMock(PostConditionCheckerInterface::class);
    }
}
