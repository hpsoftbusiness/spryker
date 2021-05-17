<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Zed\Checkout\Business;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\CustomerBalanceByCurrencyBuilder;
use Generated\Shared\DataBuilder\PaymentBuilder;
use Generated\Shared\Transfer\BenefitVoucherDealDataTransfer;
use Generated\Shared\Transfer\CustomerBalanceByCurrencyTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShoppingPointsDealTransfer;
use Pyz\Shared\DummyPrepayment\DummyPrepaymentConfig;
use Pyz\Zed\Checkout\CheckoutDependencyProvider;
use Pyz\Zed\Customer\CustomerDependencyProvider;
use Spryker\Shared\Nopayment\NopaymentConfig;
use Spryker\Zed\Calculation\Business\CalculationFacadeInterface;
use Spryker\Zed\Checkout\Business\CheckoutFacadeInterface;
use Spryker\Zed\Customer\Communication\Plugin\CustomerPreConditionCheckerPlugin;
use Spryker\Zed\Customer\Dependency\Facade\CustomerToMailInterface;
use Spryker\Zed\Discount\Communication\Plugin\Checkout\VoucherDiscountMaxUsageCheckoutPreConditionPlugin;
use Spryker\Zed\GiftCardMailConnector\Business\GiftCardMailConnectorBusinessFactory;
use Spryker\Zed\GiftCardMailConnector\Communication\Plugin\Checkout\SendEmailToGiftCardUser;
use Spryker\Zed\GiftCardMailConnector\Dependency\Facade\GiftCardMailConnectorToMailFacadeInterface;
use Spryker\Zed\GiftCardMailConnector\GiftCardMailConnectorDependencyProvider;
use Spryker\Zed\Payment\Communication\Plugin\Checkout\PaymentMethodValidityCheckoutPreConditionPlugin;
use Spryker\Zed\Payment\Communication\Plugin\Checkout\PaymentPostCheckPlugin;
use Spryker\Zed\Payment\Communication\Plugin\Checkout\PaymentPreCheckPlugin;
use Spryker\Zed\ProductDiscontinued\Communication\Plugin\Checkout\ProductDiscontinuedCheckoutPreConditionPlugin;
use Spryker\Zed\SalesOrderThreshold\Communication\Plugin\Checkout\SalesOrderThresholdCheckoutPreConditionPlugin;
use SprykerEco\Zed\Adyen\Communication\Plugin\Checkout\AdyenPostSaveHookPlugin;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Zed
 * @group Checkout
 * @group Business
 * @group Facade
 * @group CheckoutFacadeTest
 * Add your own group annotations below this line
 */
class CheckoutFacadeTest extends Unit
{
    private const PAYMENT_ID_E_VOUCHER = 1;
    private const PAYMENT_ID_E_VOUCHER_ON_BEHALF_OF_MARKETER = 2;
    private const PAYMENT_ID_CASHBACK = 6;
    private const PAYMENT_ID_SHOPPING_POINTS = 9;
    private const PAYMENT_ID_BENEFIT_VOUCHER = 10;

    /**
     * @var \PyzTest\Zed\Checkout\CheckoutBusinessTester
     */
    protected $tester;

    /**
     * @var \Spryker\Zed\Checkout\Business\CheckoutFacadeInterface
     */
    private $sut;

    /**
     * @return void
     */
    protected function _before(): void
    {
        $this->sut = $this->getFacade();
        $this->tester->setDependency(
            CheckoutDependencyProvider::CHECKOUT_PRE_CONDITIONS,
            [
                new CustomerPreConditionCheckerPlugin(),
                new PaymentPreCheckPlugin(),
                new ProductDiscontinuedCheckoutPreConditionPlugin(), #ProductDiscontinuedFeature
                new SalesOrderThresholdCheckoutPreConditionPlugin(), #SalesOrderThresholdFeature
                new VoucherDiscountMaxUsageCheckoutPreConditionPlugin(),
                new PaymentMethodValidityCheckoutPreConditionPlugin(),
            ]
        );

        $this->tester->setDependency(
            CustomerDependencyProvider::FACADE_MAIL,
            function () {
                return $this->createMock(CustomerToMailInterface::class);
            }
        );

        $this->tester->setDependency(
            CustomerDependencyProvider::PLUGINS_CUSTOMER_TRANSFER_EXPANDER,
            function () {
                return [];
            }
        );

        $this->tester->setDependency(
            CheckoutDependencyProvider::CHECKOUT_POST_HOOKS,
            function () {
                return [
                    new PaymentPostCheckPlugin(),
                    new SendEmailToGiftCardUser(),
                    new AdyenPostSaveHookPlugin(),
                ];
            }
        );

        $this->tester->setDependency(
            GiftCardMailConnectorDependencyProvider::FACADE_MAIL,
            function () {
                return $this->createMock(GiftCardMailConnectorToMailFacadeInterface::class);
            },
            GiftCardMailConnectorBusinessFactory::class
        );
    }

    /**
     * @return void
     */
    public function testCheckoutOrderIsCreatedWithoutAnyMyWorldPayments(): void
    {
        $quoteTransfer = $this->tester->buildQuoteTransfer();
        $quoteTransfer->setPayment($this->createDummyPaymentTransfer(500));

        $quoteTransfer = $this->getCalculationFacade()->recalculateQuote($quoteTransfer);

        $checkoutResponseTransfer = $this->sut->placeOrder($quoteTransfer);

        self::assertTrue($checkoutResponseTransfer->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testCheckoutOrderNotCreatedWithInvalidPaymentMethod(): void
    {
        $quoteTransfer = $this->tester->buildQuoteTransfer();
        $paymentTransfer = $this->buildPaymentTransfer([
            PaymentTransfer::PAYMENT_METHOD_NAME => 'Random method',
            PaymentTransfer::PAYMENT_PROVIDER => 'Random method',
            PaymentTransfer::PAYMENT_METHOD => 'Random method',
            PaymentTransfer::PAYMENT_SELECTION => 'Random method',
            PaymentTransfer::AMOUNT => 500,
        ]);
        $quoteTransfer->setPayment($paymentTransfer);

        $quoteTransfer = $this->getCalculationFacade()->recalculateQuote($quoteTransfer);

        $checkoutResponseTransfer = $this->sut->placeOrder($quoteTransfer);

        self::assertFalse($checkoutResponseTransfer->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testCheckoutOrderPassesWithNopaymentAndCashbackMethod(): void
    {
        $quoteTransfer = $this->tester->buildQuoteTransfer([
            QuoteTransfer::USE_CASHBACK_BALANCE => true,
        ]);

        $customerBalance = $this->buildCustomerBalanceTransfer([
            CustomerBalanceByCurrencyTransfer::PAYMENT_OPTION_ID => self::PAYMENT_ID_CASHBACK,
            CustomerBalanceByCurrencyTransfer::AVAILABLE_BALANCE => 20,
            CustomerBalanceByCurrencyTransfer::TARGET_AVAILABLE_BALANCE => 20,
        ]);
        $quoteTransfer->getCustomer()->addBalance($customerBalance);
        $quoteTransfer->setPayment($this->createNopaymentMethodTransfer());

        $quoteTransfer = $this->getCalculationFacade()->recalculateQuote($quoteTransfer);

        $checkoutResponseTransfer = $this->sut->placeOrder($quoteTransfer);

        self::assertTrue($checkoutResponseTransfer->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testCheckoutOrderPassesWithNopaymentAndEVoucherMethod(): void
    {
        $quoteTransfer = $this->tester->buildQuoteTransfer([
            QuoteTransfer::USE_E_VOUCHER_BALANCE => true,
        ]);

        $customerBalance = $this->buildCustomerBalanceTransfer([
            CustomerBalanceByCurrencyTransfer::PAYMENT_OPTION_ID => self::PAYMENT_ID_E_VOUCHER,
            CustomerBalanceByCurrencyTransfer::AVAILABLE_BALANCE => 20,
            CustomerBalanceByCurrencyTransfer::TARGET_AVAILABLE_BALANCE => 20,
        ]);
        $quoteTransfer->getCustomer()->addBalance($customerBalance);
        $quoteTransfer->setPayment($this->createNopaymentMethodTransfer());

        $quoteTransfer = $this->getCalculationFacade()->recalculateQuote($quoteTransfer);

        $checkoutResponseTransfer = $this->sut->placeOrder($quoteTransfer);

        self::assertTrue($checkoutResponseTransfer->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testCheckoutOrderPassesWithNopaymentAndEVoucherOnBehalfOfMarketerMethod(): void
    {
        $quoteTransfer = $this->tester->buildQuoteTransfer([
            QuoteTransfer::USE_E_VOUCHER_ON_BEHALF_OF_MARKETER => true,
        ]);

        $customerBalance = $this->buildCustomerBalanceTransfer([
            CustomerBalanceByCurrencyTransfer::PAYMENT_OPTION_ID => self::PAYMENT_ID_E_VOUCHER_ON_BEHALF_OF_MARKETER,
            CustomerBalanceByCurrencyTransfer::AVAILABLE_BALANCE => 20,
            CustomerBalanceByCurrencyTransfer::TARGET_AVAILABLE_BALANCE => 20,
        ]);
        $quoteTransfer->getCustomer()->addBalance($customerBalance);
        $quoteTransfer->setPayment($this->createNopaymentMethodTransfer());

        $quoteTransfer = $this->getCalculationFacade()->recalculateQuote($quoteTransfer);

        $checkoutResponseTransfer = $this->sut->placeOrder($quoteTransfer);

        self::assertTrue($checkoutResponseTransfer->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testCheckoutOrderFailsWithBenefitVoucherAndNopaymentMethod(): void
    {
        $quoteTransfer = $this->tester->buildQuoteTransfer([
            QuoteTransfer::TOTAL_USED_BENEFIT_VOUCHERS_AMOUNT => 400,
        ], [
            $this->getBenefitVoucherItemDataFixture(),
        ]);

        $customerBalance = $this->buildCustomerBalanceTransfer([
            CustomerBalanceByCurrencyTransfer::PAYMENT_OPTION_ID => self::PAYMENT_ID_BENEFIT_VOUCHER,
            CustomerBalanceByCurrencyTransfer::AVAILABLE_BALANCE => 20,
            CustomerBalanceByCurrencyTransfer::TARGET_AVAILABLE_BALANCE => 20,
        ]);
        $quoteTransfer->getCustomer()->addBalance($customerBalance);
        $quoteTransfer->setPayment($this->createNopaymentMethodTransfer());

        $quoteTransfer = $this->getCalculationFacade()->recalculateQuote($quoteTransfer);

        $checkoutResponseTransfer = $this->sut->placeOrder($quoteTransfer);

        self::assertFalse($checkoutResponseTransfer->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testCheckoutOrderPlacedWithBenefitVoucherPaymentMethod(): void
    {
        $quoteTransfer = $this->tester->buildQuoteTransfer([
            QuoteTransfer::TOTAL_USED_BENEFIT_VOUCHERS_AMOUNT => 400,
        ], [
            $this->getBenefitVoucherItemDataFixture(),
        ]);

        $customerBalance = $this->buildCustomerBalanceTransfer([
            CustomerBalanceByCurrencyTransfer::PAYMENT_OPTION_ID => self::PAYMENT_ID_BENEFIT_VOUCHER,
            CustomerBalanceByCurrencyTransfer::AVAILABLE_BALANCE => 20,
            CustomerBalanceByCurrencyTransfer::TARGET_AVAILABLE_BALANCE => 20,
        ]);
        $quoteTransfer->getCustomer()->addBalance($customerBalance);
        $quoteTransfer->setPayment($this->createDummyPaymentTransfer(2100));

        $quoteTransfer = $this->getCalculationFacade()->recalculateQuote($quoteTransfer);

        $checkoutResponseTransfer = $this->sut->placeOrder($quoteTransfer);

        self::assertTrue($checkoutResponseTransfer->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testCheckoutOrderFailsWithShoppingPointsAndNopaymentMethod(): void
    {
        $quoteTransfer = $this->tester->buildQuoteTransfer([
            QuoteTransfer::TOTAL_USED_SHOPPING_POINTS_AMOUNT => 10,
        ], [
            $this->getShoppingPointsItemDataFixture(),
        ]);

        $customerBalance = $this->buildCustomerBalanceTransfer([
            CustomerBalanceByCurrencyTransfer::PAYMENT_OPTION_ID => self::PAYMENT_ID_SHOPPING_POINTS,
            CustomerBalanceByCurrencyTransfer::AVAILABLE_BALANCE => 20,
            CustomerBalanceByCurrencyTransfer::TARGET_AVAILABLE_BALANCE => 20,
        ]);
        $quoteTransfer->getCustomer()->addBalance($customerBalance);
        $quoteTransfer->setPayment($this->createNopaymentMethodTransfer());

        $quoteTransfer = $this->getCalculationFacade()->recalculateQuote($quoteTransfer);

        $checkoutResponseTransfer = $this->sut->placeOrder($quoteTransfer);

        self::assertFalse($checkoutResponseTransfer->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testCheckoutOrderPlacedWithShoppingPointsPaymentMethod(): void
    {
        $quoteTransfer = $this->tester->buildQuoteTransfer([
            QuoteTransfer::TOTAL_USED_SHOPPING_POINTS_AMOUNT => 10,
        ], [
            $this->getShoppingPointsItemDataFixture(),
        ]);

        $customerBalance = $this->buildCustomerBalanceTransfer([
            CustomerBalanceByCurrencyTransfer::PAYMENT_OPTION_ID => self::PAYMENT_ID_SHOPPING_POINTS,
            CustomerBalanceByCurrencyTransfer::AVAILABLE_BALANCE => 20,
        ]);
        $quoteTransfer->getCustomer()->addBalance($customerBalance);
        $quoteTransfer->setPayment($this->createDummyPaymentTransfer(2100));

        $quoteTransfer = $this->getCalculationFacade()->recalculateQuote($quoteTransfer);

        $checkoutResponseTransfer = $this->sut->placeOrder($quoteTransfer);

        self::assertTrue($checkoutResponseTransfer->getIsSuccess());
    }

    /**
     * @return array
     */
    private function getBenefitVoucherItemDataFixture(): array
    {
        return [
            ItemTransfer::UNIT_PRICE => 1000,
            ItemTransfer::UNIT_GROSS_PRICE => 1000,
            ItemTransfer::UNIT_NET_PRICE => 850,
            ItemTransfer::ORIGIN_UNIT_GROSS_PRICE => 1000,
            ItemTransfer::QUANTITY => 2,
            ItemTransfer::BENEFIT_VOUCHER_DEAL_DATA => [
                BenefitVoucherDealDataTransfer::IS_STORE => true,
                BenefitVoucherDealDataTransfer::SALES_PRICE => 800,
                BenefitVoucherDealDataTransfer::AMOUNT => 200,
            ],
            ItemTransfer::USE_BENEFIT_VOUCHER => true,
        ];
    }

    /**
     * @return array
     */
    private function getShoppingPointsItemDataFixture(): array
    {
        return [
            ItemTransfer::UNIT_PRICE => 1000,
            ItemTransfer::UNIT_GROSS_PRICE => 1000,
            ItemTransfer::UNIT_NET_PRICE => 850,
            ItemTransfer::ORIGIN_UNIT_GROSS_PRICE => 1000,
            ItemTransfer::QUANTITY => 2,
            ItemTransfer::SHOPPING_POINTS_DEAL => [
                ShoppingPointsDealTransfer::IS_ACTIVE => true,
                ShoppingPointsDealTransfer::PRICE => 800,
                ShoppingPointsDealTransfer::SHOPPING_POINTS_QUANTITY => 5,
            ],
            ItemTransfer::USE_SHOPPING_POINTS => true,
        ];
    }

    /**
     * @return \Generated\Shared\Transfer\PaymentTransfer
     */
    private function createNopaymentMethodTransfer(): PaymentTransfer
    {
        return $this->buildPaymentTransfer([
            PaymentTransfer::PAYMENT_METHOD_NAME => NopaymentConfig::PAYMENT_METHOD_NAME,
            PaymentTransfer::PAYMENT_METHOD => NopaymentConfig::PAYMENT_METHOD_NAME,
            PaymentTransfer::PAYMENT_PROVIDER => NopaymentConfig::PAYMENT_PROVIDER_NAME,
            PaymentTransfer::PAYMENT_SELECTION => NopaymentConfig::PAYMENT_PROVIDER_NAME,
            PaymentTransfer::AMOUNT => 0,
            PaymentTransfer::IS_LIMITED_AMOUNT => true,
        ]);
    }

    /**
     * @param int $amount
     *
     * @return \Generated\Shared\Transfer\PaymentTransfer
     */
    private function createDummyPaymentTransfer(int $amount): PaymentTransfer
    {
        return $this->buildPaymentTransfer([
            PaymentTransfer::PAYMENT_METHOD_NAME => DummyPrepaymentConfig::DUMMY_PREPAYMENT,
            PaymentTransfer::PAYMENT_PROVIDER => DummyPrepaymentConfig::PROVIDER_NAME,
            PaymentTransfer::PAYMENT_METHOD => DummyPrepaymentConfig::DUMMY_PREPAYMENT,
            PaymentTransfer::PAYMENT_SELECTION => DummyPrepaymentConfig::DUMMY_PREPAYMENT,
            PaymentTransfer::AMOUNT => $amount,
        ]);
    }

    /**
     * @param array $override
     *
     * @return \Generated\Shared\Transfer\CustomerBalanceByCurrencyTransfer
     */
    private function buildCustomerBalanceTransfer(array $override): CustomerBalanceByCurrencyTransfer
    {
        return (new CustomerBalanceByCurrencyBuilder($override))->build();
    }

    /**
     * @param array $override
     *
     * @return \Generated\Shared\Transfer\PaymentTransfer
     */
    private function buildPaymentTransfer(array $override): PaymentTransfer
    {
        return (new PaymentBuilder($override))->build();
    }

    /**
     * @return \Spryker\Zed\Checkout\Business\CheckoutFacadeInterface
     */
    private function getFacade(): CheckoutFacadeInterface
    {
        return $this->tester->getLocator()->checkout()->facade();
    }

    /**
     * @return \Spryker\Zed\Calculation\Business\CalculationFacadeInterface
     */
    private function getCalculationFacade(): CalculationFacadeInterface
    {
        return $this->tester->getLocator()->calculation()->facade();
    }
}
