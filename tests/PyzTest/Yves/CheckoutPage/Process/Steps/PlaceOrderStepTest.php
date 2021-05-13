<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Yves\CheckoutPage\Process\Steps;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Pyz\Yves\CheckoutPage\Plugin\Router\CheckoutPageRouteProviderPlugin;
use Pyz\Yves\CheckoutPage\Process\Steps\PlaceOrderStep;
use Pyz\Yves\CheckoutPage\Process\Steps\ProductSellableChecker\ProductSellableChecker;
use Spryker\Shared\Translator\TranslatorInterface;
use Spryker\Yves\Messenger\FlashMessenger\FlashMessengerInterface;
use SprykerShop\Yves\CartPage\Plugin\Router\CartPageRouteProviderPlugin;
use SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToCheckoutClientInterface;
use SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToGlossaryStorageClientInterface;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Yves
 * @group CheckoutPage
 * @group Process
 * @group Steps
 * @group PlaceOrderStepTest
 * Add your own group annotations below this line
 */
class PlaceOrderStepTest extends Unit
{
    private const STEP_ROUTE = 'place-order-step-test-route';
    private const STEP_ESCAPE_ROUTE = 'place-order-step-test-escape-route';

    private const LOCALE_NAME = 'DE';

    /**
     * @var \PyzTest\Yves\CheckoutPage\CheckoutPageProcessTester
     */
    protected $tester;

    /**
     * @var \Pyz\Yves\CheckoutPage\Process\Steps\PlaceOrderStep
     */
    private $sut;

    /**
     * @var \Spryker\Yves\Messenger\FlashMessenger\FlashMessengerInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private $flashMessengerMock;

    /**
     * @return void
     */
    protected function _before(): void
    {
        $this->flashMessengerMock = $this->mockFlashMessenger();
        $this->sut = $this->createPlaceOrderStep();
    }

    /**
     * @return void
     */
    public function testPreConditionCheckPassesIfCartNotEmptyAndAllProductsSellableForCountry(): void
    {
        $quoteTransfer = $this->tester->buildQuoteTransfer([
            QuoteTransfer::CHECKOUT_CONFIRMED => true,
        ]);
        $this->flashMessengerMock
            ->expects(self::never())
            ->method('addErrorMessage');

        $isValid = $this->sut->preCondition($quoteTransfer);

        self::assertTrue($isValid);
    }

    /**
     * @return void
     */
    public function testPreConditionCheckFailsIfCartEmpty(): void
    {
        $quoteTransfer = $this->tester->buildQuoteTransfer();
        $quoteTransfer->setItems(new ArrayObject());
        $isValid = $this->sut->preCondition($quoteTransfer);

        self::assertFalse($isValid);
    }

    /**
     * @return void
     */
    public function testPreConditionCheckFailsIfCheckoutNotConfirmed(): void
    {
        $quoteTransfer = $this->tester->buildQuoteTransfer();
        $isValid = $this->sut->preCondition($quoteTransfer);

        self::assertFalse($isValid);
        self::assertEquals(
            CheckoutPageRouteProviderPlugin::ROUTE_NAME_CHECKOUT_SUMMARY,
            $this->sut->getEscapeRoute()
        );
    }

    /**
     * @return void
     */
    public function testPreConditionCheckFailsIfItemNotSellableForCustomerCountry(): void
    {
        $quoteTransfer = $this->tester->buildQuoteTransfer([
            QuoteTransfer::CHECKOUT_CONFIRMED => true,
        ], [
            [
                ItemTransfer::CONCRETE_ATTRIBUTES => [
                    'sellable_nl' => true,
                ],
            ],
        ]);

        $this->flashMessengerMock
            ->expects(self::once())
            ->method('addErrorMessage');

        $isValid = $this->sut->preCondition($quoteTransfer);

        self::assertFalse($isValid);
        self::assertEquals(
            CartPageRouteProviderPlugin::ROUTE_NAME_CART,
            $this->sut->getEscapeRoute()
        );
    }

    /**
     * @return \Pyz\Yves\CheckoutPage\Process\Steps\PlaceOrderStep
     */
    private function createPlaceOrderStep(): PlaceOrderStep
    {
        return new PlaceOrderStep(
            $this->mockCheckoutClient(),
            $this->flashMessengerMock,
            self::LOCALE_NAME,
            $this->mockGlossaryStorageClient(),
            self::STEP_ROUTE,
            self::STEP_ESCAPE_ROUTE,
            $this->createProductSellableChecker()
        );
    }

    /**
     * @return \SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToCheckoutClientInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private function mockCheckoutClient(): CheckoutPageToCheckoutClientInterface
    {
        return $this->createMock(CheckoutPageToCheckoutClientInterface::class);
    }

    /**
     * @return \Spryker\Yves\Messenger\FlashMessenger\FlashMessengerInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private function mockFlashMessenger(): FlashMessengerInterface
    {
        return $this->createMock(FlashMessengerInterface::class);
    }

    /**
     * @return \SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToGlossaryStorageClientInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private function mockGlossaryStorageClient(): CheckoutPageToGlossaryStorageClientInterface
    {
        return $this->createMock(CheckoutPageToGlossaryStorageClientInterface::class);
    }

    /**
     * @return \Spryker\Shared\Translator\TranslatorInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private function mockTranslator(): TranslatorInterface
    {
        return $this->createMock(TranslatorInterface::class);
    }

    /**
     * @return \Pyz\Yves\CheckoutPage\Process\Steps\ProductSellableChecker\ProductSellableChecker
     */
    private function createProductSellableChecker(): ProductSellableChecker
    {
        return new ProductSellableChecker(
            $this->flashMessengerMock,
            $this->mockTranslator()
        );
    }
}
