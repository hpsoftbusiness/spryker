<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Yves\CheckoutPage\Process\Steps;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\CustomerBuilder;
use Generated\Shared\DataBuilder\PaymentBuilder;
use Generated\Shared\Transfer\BenefitVoucherDealDataTransfer;
use Generated\Shared\Transfer\CustomerBalanceByCurrencyTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\MyWorldApiResponseTransfer;
use Generated\Shared\Transfer\PaymentSessionResponseTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShoppingPointsDealTransfer;
use Pyz\Client\MyWorldPayment\MyWorldPaymentClientInterface;
use Pyz\Shared\DummyPrepayment\DummyPrepaymentConfig;
use Pyz\Yves\CheckoutPage\Plugin\Router\CheckoutPageRouteProviderPlugin;
use Pyz\Yves\CheckoutPage\Process\Steps\PaymentStep;
use Pyz\Yves\CheckoutPage\Process\Steps\PaymentStep\PaymentPreConditionChecker;
use Pyz\Yves\CheckoutPage\Process\Steps\PreConditionCheckerInterface;
use Pyz\Yves\CheckoutPage\Process\Steps\ProductSellableChecker\ProductSellableChecker;
use Pyz\Yves\CheckoutPage\Process\Steps\ProductSellableChecker\ProductSellableCheckerInterface;
use Pyz\Yves\DummyPrepayment\Plugin\StepEngine\DummyPaymentStepHandlerPlugin;
use Pyz\Zed\MyWorldPayment\MyWorldPaymentConfig;
use PyzTest\Yves\CheckoutPage\CheckoutPageProcessTester;
use Spryker\Shared\Money\Converter\IntegerToDecimalConverter;
use Spryker\Shared\Nopayment\NopaymentConfig;
use Spryker\Shared\Translator\TranslatorInterface;
use Spryker\Yves\Messenger\FlashMessenger\FlashMessengerInterface;
use Spryker\Yves\Nopayment\Plugin\NopaymentHandlerPlugin;
use Spryker\Yves\StepEngine\Dependency\Plugin\Handler\StepHandlerPluginCollection;
use SprykerEco\Shared\Adyen\AdyenConfig;
use SprykerEco\Yves\Adyen\Plugin\AdyenPaymentHandlerPlugin;
use SprykerShop\Yves\CartPage\Plugin\Router\CartPageRouteProviderPlugin;
use SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToCalculationClientInterface;
use SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToPaymentClientInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Yves
 * @group CheckoutPage
 * @group Process
 * @group Steps
 * @group PaymentStepTest
 * Add your own group annotations below this line
 */
class PaymentStepTest extends Unit
{
    private const STEP_ROUTE = 'payment-step-test-route';
    private const STEP_ESCAPE_ROUTE = 'payment-step-test-escape-route';

    /**
     * @var \PyzTest\Yves\CheckoutPage\CheckoutPageProcessTester
     */
    protected $tester;

    /**
     * @var \Pyz\Yves\CheckoutPage\Process\Steps\PaymentStep
     */
    private $sut;

    /**
     * @var \Pyz\Client\MyWorldPayment\MyWorldPaymentClientInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private $myWorldPaymentClientMock;

    /**
     * @var \SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToPaymentClientInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private $paymentClientMock;

    /**
     * @var \SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToCalculationClientInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private $calculationClientMock;

    /**
     * @return void
     */
    protected function _before(): void
    {
        $this->myWorldPaymentClientMock = $this->mockMyWorldPaymentClient();
        $this->paymentClientMock = $this->mockPaymentClient();
        $this->calculationClientMock = $this->mockCalculationClient();
        $this->sut = $this->createPaymentStep();
    }

    /**
     * @return void
     */
    public function testQuoteItemsNotSellableForCustomerCountryCausesPreConditionCheckFail(): void
    {
        $customerTransfer = $this->buildCustomerTransfer();
        $quoteTransfer = $this->tester->buildQuoteTransfer([
            QuoteTransfer::CUSTOMER => $customerTransfer->toArray(),
        ], [
            [
                ItemTransfer::ID_PRODUCT_ABSTRACT => 2,
                ItemTransfer::UNIT_GROSS_PRICE => 1200,
                ItemTransfer::ORIGIN_UNIT_GROSS_PRICE => 1200,
                ItemTransfer::QUANTITY => 2,
            ],
        ]);

        $isQuoteValid = $this->sut->preCondition($quoteTransfer);
        self::assertFalse($isQuoteValid);
        self::assertEquals(
            CartPageRouteProviderPlugin::ROUTE_NAME_CART,
            $this->sut->getEscapeRoute()
        );
    }

    /**
     * @return void
     */
    public function testNotEnoughShoppingPointBalanceCausesPreConditionFail(): void
    {
        $customerTransfer = $this->buildCustomerTransfer();
        $quoteTransfer = $this->tester->buildQuoteTransfer([
            QuoteTransfer::CUSTOMER => $customerTransfer->toArray(),
        ], [
            [
                ItemTransfer::ID_PRODUCT_ABSTRACT => 2,
                ItemTransfer::UNIT_GROSS_PRICE => 1200,
                ItemTransfer::ORIGIN_UNIT_GROSS_PRICE => 1200,
                ItemTransfer::QUANTITY => 2,
                ItemTransfer::USE_SHOPPING_POINTS => true,
                ItemTransfer::SHOPPING_POINTS_DEAL => [
                    ShoppingPointsDealTransfer::IS_ACTIVE => true,
                    ShoppingPointsDealTransfer::SHOPPING_POINTS_QUANTITY => 5,
                    ShoppingPointsDealTransfer::PRICE => 1000,
                ],
                ItemTransfer::CONCRETE_ATTRIBUTES => [
                    'sellable_de' => true,
                ],
            ],
        ]);

        $isQuoteValid = $this->sut->preCondition($quoteTransfer);
        self::assertFalse($isQuoteValid);
        self::assertEquals(
            CheckoutPageRouteProviderPlugin::ROUTE_NAME_CHECKOUT_BENEFIT,
            $this->sut->getEscapeRoute()
        );
    }

    /**
     * @group testNotEnoughBenefitVouchersBalanceCausesPreConditionFail
     *
     * @return void
     */
    public function testNotEnoughBenefitVouchersBalanceCausesPreConditionFail(): void
    {
        $customerTransfer = $this->buildCustomerTransfer();
        $quoteTransfer = $this->tester->buildQuoteTransfer([
            QuoteTransfer::CUSTOMER => $customerTransfer->toArray(),
            QuoteTransfer::TOTAL_USED_BENEFIT_VOUCHERS_AMOUNT => 400,
            QuoteTransfer::USE_BENEFIT_VOUCHER => true,
        ], [
            [
                ItemTransfer::ID_PRODUCT_ABSTRACT => 2,
                ItemTransfer::UNIT_GROSS_PRICE => 1200,
                ItemTransfer::ORIGIN_UNIT_GROSS_PRICE => 1200,
                ItemTransfer::QUANTITY => 2,
                ItemTransfer::USE_BENEFIT_VOUCHER => true,
                ItemTransfer::CONCRETE_ATTRIBUTES => [
                    'sellable_de' => true,
                ],
            ],
        ]);

        $isQuoteValid = $this->sut->preCondition($quoteTransfer);
        self::assertFalse($isQuoteValid);
        self::assertEquals(
            CheckoutPageRouteProviderPlugin::ROUTE_NAME_CHECKOUT_BENEFIT,
            $this->sut->getEscapeRoute()
        );
    }

    /**
     * @return void
     */
    public function testQuoteWithShoppingPointsItemPassesPaymentsStep(): void
    {
        $this->myWorldPaymentClientMock
            ->expects(self::once())
            ->method('createPaymentSession')
            ->willReturn(
                (new MyWorldApiResponseTransfer())->setIsSuccess(true)
                ->setPaymentSessionResponse(
                    (new PaymentSessionResponseTransfer())
                        ->setSessionId(CheckoutPageProcessTester::PAYMENT_SESSION_ID)
                )
            );

        $customerTransfer = $this->buildCustomerTransfer([
            [
                CustomerBalanceByCurrencyTransfer::PAYMENT_OPTION_ID => 9,
                CustomerBalanceByCurrencyTransfer::AVAILABLE_BALANCE => 25.50,
            ],
        ]);

        $quoteTransfer = $this->tester->buildQuoteTransfer([
            QuoteTransfer::CUSTOMER => $customerTransfer->toArray(),
        ], [
            [
                ItemTransfer::ID_PRODUCT_ABSTRACT => 2,
                ItemTransfer::UNIT_GROSS_PRICE => 1200,
                ItemTransfer::ORIGIN_UNIT_GROSS_PRICE => 1200,
                ItemTransfer::QUANTITY => 2,
                ItemTransfer::USE_SHOPPING_POINTS => true,
                ItemTransfer::SHOPPING_POINTS_DEAL => [
                    ShoppingPointsDealTransfer::IS_ACTIVE => true,
                    ShoppingPointsDealTransfer::SHOPPING_POINTS_QUANTITY => 5,
                    ShoppingPointsDealTransfer::PRICE => 1000,
                ],
                ItemTransfer::CONCRETE_ATTRIBUTES => [
                    'sellable_de' => true,
                ],
            ],
        ]);

        $quoteTransfer->setPayment($this->buildPaymentTransfer());

        $isQuoteValid = $this->sut->preCondition($quoteTransfer);
        self::assertTrue($isQuoteValid);

        $this->sut->execute(new Request(), $quoteTransfer);

        self::assertEquals(
            CheckoutPageProcessTester::PAYMENT_SESSION_ID,
            $quoteTransfer->getMyWorldPaymentSessionId()
        );

        self::assertFalse($quoteTransfer->getMyWorldPaymentIsSmsAuthenticationRequired());

        $isQuoteValid = $this->sut->postCondition($quoteTransfer);

        self::assertTrue($isQuoteValid);
    }

    /**
     * @return void
     */
    public function testQuoteWithBenefitVoucherItemPassesPaymentsStep(): void
    {
        $this->mockCreatePaymentSessionWithSuccessfulResultsAnd2FAuth();

        $customerTransfer = $this->buildCustomerTransfer([
            [
                CustomerBalanceByCurrencyTransfer::PAYMENT_OPTION_ID => 10,
                CustomerBalanceByCurrencyTransfer::AVAILABLE_BALANCE => 25.50,
            ],
        ]);

        $quoteTransfer = $this->tester->buildQuoteTransfer([
            QuoteTransfer::CUSTOMER => $customerTransfer->toArray(),
            QuoteTransfer::USE_BENEFIT_VOUCHER => true,
            QuoteTransfer::TOTAL_USED_BENEFIT_VOUCHERS_AMOUNT => 100,
        ], [
            [
                ItemTransfer::ID_PRODUCT_ABSTRACT => 2,
                ItemTransfer::UNIT_GROSS_PRICE => 1200,
                ItemTransfer::ORIGIN_UNIT_GROSS_PRICE => 1200,
                ItemTransfer::QUANTITY => 2,
                ItemTransfer::USE_BENEFIT_VOUCHER => true,
                ItemTransfer::BENEFIT_VOUCHER_DEAL_DATA => [
                    BenefitVoucherDealDataTransfer::IS_STORE => true,
                    BenefitVoucherDealDataTransfer::AMOUNT => 100,
                    BenefitVoucherDealDataTransfer::SALES_PRICE => 1100,
                ],
                ItemTransfer::CONCRETE_ATTRIBUTES => [
                    'sellable_de' => true,
                ],
            ],
        ]);

        $quoteTransfer->setPayment($this->buildPaymentTransfer());

        $isQuoteValid = $this->sut->preCondition($quoteTransfer);
        self::assertTrue($isQuoteValid);

        $this->sut->execute(new Request(), $quoteTransfer);

        self::assertEquals(
            CheckoutPageProcessTester::PAYMENT_SESSION_ID,
            $quoteTransfer->getMyWorldPaymentSessionId()
        );
        self::assertTrue($quoteTransfer->getMyWorldPaymentIsSmsAuthenticationRequired());
        self::assertCount(1, $quoteTransfer->getPayments());
        self::assertEquals(
            MyWorldPaymentConfig::PAYMENT_METHOD_BENEFIT_VOUCHER_NAME,
            $quoteTransfer->getPayments()[0]->getPaymentMethodName()
        );

        $isQuoteValid = $this->sut->postCondition($quoteTransfer);

        self::assertTrue($isQuoteValid);
    }

    /**
     * @return void
     */
    public function testQuoteCashbackPaymentSelectedPassesPaymentsStep(): void
    {
        $this->mockCreatePaymentSessionWithSuccessfulResultsAnd2FAuth();

        $customerTransfer = $this->buildCustomerTransfer([
            [
                CustomerBalanceByCurrencyTransfer::PAYMENT_OPTION_ID => 6,
                CustomerBalanceByCurrencyTransfer::AVAILABLE_BALANCE => 25.50,
            ],
        ]);

        $quoteTransfer = $this->tester->buildQuoteTransfer([
            QuoteTransfer::CUSTOMER => $customerTransfer->toArray(),
            QuoteTransfer::USE_CASHBACK_BALANCE => true,
        ]);

        $quoteTransfer->setPayment($this->buildPaymentTransfer());

        $isQuoteValid = $this->sut->preCondition($quoteTransfer);
        self::assertTrue($isQuoteValid);

        $this->sut->execute(new Request(), $quoteTransfer);

        $this->assertFullPriceToPayCoveringPaymentMethodPassedSuccessfully(
            $quoteTransfer,
            MyWorldPaymentConfig::PAYMENT_METHOD_CASHBACK_NAME
        );

        $isQuoteValid = $this->sut->postCondition($quoteTransfer);

        self::assertTrue($isQuoteValid);
    }

    /**
     * @return void
     */
    public function testQuoteEVoucherPaymentSelectedPassesPaymentsStep(): void
    {
        $this->mockCreatePaymentSessionWithSuccessfulResultsAnd2FAuth();

        $customerTransfer = $this->buildCustomerTransfer([
            [
                CustomerBalanceByCurrencyTransfer::PAYMENT_OPTION_ID => 1,
                CustomerBalanceByCurrencyTransfer::AVAILABLE_BALANCE => 25.50,
            ],
        ]);

        $quoteTransfer = $this->tester->buildQuoteTransfer([
            QuoteTransfer::CUSTOMER => $customerTransfer->toArray(),
            QuoteTransfer::USE_E_VOUCHER_BALANCE => true,
        ]);

        $quoteTransfer->setPayment($this->buildPaymentTransfer());

        $isQuoteValid = $this->sut->preCondition($quoteTransfer);
        self::assertTrue($isQuoteValid);

        $this->sut->execute(new Request(), $quoteTransfer);

        $this->assertFullPriceToPayCoveringPaymentMethodPassedSuccessfully(
            $quoteTransfer,
            MyWorldPaymentConfig::PAYMENT_METHOD_EVOUCHER_NAME
        );

        $isQuoteValid = $this->sut->postCondition($quoteTransfer);
        self::assertTrue($isQuoteValid);
    }

    /**
     * @return void
     */
    public function testQuoteEVoucherOnBehalfOfMarketerPaymentSelectedPassesPaymentsStep(): void
    {
        $this->mockCreatePaymentSessionWithSuccessfulResultsAnd2FAuth();

        $customerTransfer = $this->buildCustomerTransfer([
            [
                CustomerBalanceByCurrencyTransfer::PAYMENT_OPTION_ID => 2,
                CustomerBalanceByCurrencyTransfer::AVAILABLE_BALANCE => 25.50,
            ],
        ]);

        $quoteTransfer = $this->tester->buildQuoteTransfer([
            QuoteTransfer::CUSTOMER => $customerTransfer->toArray(),
            QuoteTransfer::USE_E_VOUCHER_ON_BEHALF_OF_MARKETER => true,
        ]);

        $quoteTransfer->setPayment($this->buildPaymentTransfer());

        $isQuoteValid = $this->sut->preCondition($quoteTransfer);
        self::assertTrue($isQuoteValid);

        $this->sut->execute(new Request(), $quoteTransfer);

        $this->assertFullPriceToPayCoveringPaymentMethodPassedSuccessfully(
            $quoteTransfer,
            MyWorldPaymentConfig::PAYMENT_METHOD_EVOUCHER_ON_BEHALF_OF_MARKETER_NAME
        );

        $isQuoteValid = $this->sut->postCondition($quoteTransfer);

        self::assertTrue($isQuoteValid);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $paymentMethod
     *
     * @return void
     */
    private function assertFullPriceToPayCoveringPaymentMethodPassedSuccessfully(
        QuoteTransfer $quoteTransfer,
        string $paymentMethod
    ): void {
        self::assertEquals(
            CheckoutPageProcessTester::PAYMENT_SESSION_ID,
            $quoteTransfer->getMyWorldPaymentSessionId()
        );
        self::assertTrue($quoteTransfer->getMyWorldPaymentIsSmsAuthenticationRequired());
        self::assertCount(1, $quoteTransfer->getPayments());
        self::assertEquals(
            $paymentMethod,
            current($quoteTransfer->getPayments())->getPaymentMethodName()
        );
        self::assertEquals($quoteTransfer->getTotals()->getGrandTotal(), current($quoteTransfer->getPayments())->getAmount());

        self::assertEquals(
            NopaymentConfig::PAYMENT_PROVIDER_NAME,
            $quoteTransfer->getPayment()->getPaymentSelection()
        );
        self::assertEquals(0, $quoteTransfer->getTotals()->getPriceToPay());
    }

    /**
     * @return void
     */
    private function mockCreatePaymentSessionWithSuccessfulResultsAnd2FAuth(): void
    {
        $this->myWorldPaymentClientMock
            ->expects(self::once())
            ->method('createPaymentSession')
            ->willReturn(
                (new MyWorldApiResponseTransfer())->setIsSuccess(true)
                    ->setPaymentSessionResponse(
                        (new PaymentSessionResponseTransfer())
                            ->setSessionId(CheckoutPageProcessTester::PAYMENT_SESSION_ID)
                            ->setTwoFactorAuth(['SMS'])
                    )
            );
    }

    /**
     * @return \Pyz\Yves\CheckoutPage\Process\Steps\PaymentStep
     */
    private function createPaymentStep(): PaymentStep
    {
        return new PaymentStep(
            $this->mockTranslator(),
            $this->myWorldPaymentClientMock,
            $this->paymentClientMock,
            $this->createPaymentMethodHandlerCollection(),
            self::STEP_ROUTE,
            self::STEP_ESCAPE_ROUTE,
            $this->mockFlashMessenger(),
            $this->calculationClientMock,
            [],
            $this->createProductSellableChecker(),
            $this->createPreConditionChecker()
        );
    }

    /**
     * @return \Spryker\Shared\Translator\TranslatorInterface
     */
    private function mockTranslator(): TranslatorInterface
    {
        return $this->createMock(TranslatorInterface::class);
    }

    /**
     * @return \Spryker\Yves\Messenger\FlashMessenger\FlashMessengerInterface
     */
    private function mockFlashMessenger(): FlashMessengerInterface
    {
        return $this->createMock(FlashMessengerInterface::class);
    }

    /**
     * @return \Pyz\Yves\CheckoutPage\Process\Steps\ProductSellableChecker\ProductSellableCheckerInterface
     */
    private function createProductSellableChecker(): ProductSellableCheckerInterface
    {
        return new ProductSellableChecker(
            $this->mockFlashMessenger(),
            $this->mockTranslator()
        );
    }

    /**
     * @return \Pyz\Yves\CheckoutPage\Process\Steps\PreConditionCheckerInterface
     */
    private function createPreConditionChecker(): PreConditionCheckerInterface
    {
        return new PaymentPreConditionChecker(
            $this->mockFlashMessenger(),
            $this->mockTranslator(),
            $this->tester->getLocator()->customer()->service(),
            new IntegerToDecimalConverter()
        );
    }

    /**
     * @return \Pyz\Client\MyWorldPayment\MyWorldPaymentClientInterface
     */
    private function mockMyWorldPaymentClient(): MyWorldPaymentClientInterface
    {
        return $this->createMock(MyWorldPaymentClientInterface::class);
    }

    /**
     * @return \SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToPaymentClientInterface
     */
    private function mockPaymentClient(): CheckoutPageToPaymentClientInterface
    {
        $mock = $this->createMock(CheckoutPageToPaymentClientInterface::class);
        /**
         * Bypassing the client and calling Zed facade directly.
         */
        $mock->method('getAvailableMethods')
            ->willReturnCallback(function (QuoteTransfer $quoteTransfer) {
                return $this->tester->getLocator()->payment()->facade()->getAvailableMethods($quoteTransfer);
            });

        return $mock;
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Plugin\Handler\StepHandlerPluginCollection
     */
    private function createPaymentMethodHandlerCollection(): StepHandlerPluginCollection
    {
        $paymentMethodHandlerCollection = new StepHandlerPluginCollection();
        $paymentMethodHandlerCollection->add(new NopaymentHandlerPlugin(), NopaymentConfig::PAYMENT_PROVIDER_NAME);
        $paymentMethodHandlerCollection->add(new AdyenPaymentHandlerPlugin(), AdyenConfig::ADYEN_CREDIT_CARD);
        $paymentMethodHandlerCollection->add(new DummyPaymentStepHandlerPlugin(), DummyPrepaymentConfig::DUMMY_PREPAYMENT);

        return $paymentMethodHandlerCollection;
    }

    /**
     * @return \SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToCalculationClientInterface
     */
    private function mockCalculationClient(): CheckoutPageToCalculationClientInterface
    {
        $mock = $this->createMock(CheckoutPageToCalculationClientInterface::class);
        /**
         * Bypassing the client and calling Zed facade directly.
         */
        $mock->method('recalculate')
            ->willReturnCallback(function (QuoteTransfer $quoteTransfer): QuoteTransfer {
                return $this->tester->getLocator()->calculation()->facade()->recalculateQuote($quoteTransfer);
            });

        return $mock;
    }

    /**
     * @param string $paymentSelection
     *
     * @return \Generated\Shared\Transfer\PaymentTransfer
     */
    private function buildPaymentTransfer(string $paymentSelection = DummyPrepaymentConfig::DUMMY_PREPAYMENT): PaymentTransfer
    {
        return (new PaymentBuilder([
            PaymentTransfer::PAYMENT_SELECTION => $paymentSelection,
        ]))->build();
    }

    /**
     * @param array $balances
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    private function buildCustomerTransfer(array $balances = []): CustomerTransfer
    {
        return (new CustomerBuilder([
            CustomerTransfer::BALANCES => $balances,
            CustomerTransfer::CUSTOMER_REFERENCE => 'TEST_001',
        ]))->build();
    }
}
