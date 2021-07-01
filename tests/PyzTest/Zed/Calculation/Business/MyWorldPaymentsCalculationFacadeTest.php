<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Zed\Calculation\Business;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\CustomerBalanceByCurrencyBuilder;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\DataBuilder\PaymentBuilder;
use Generated\Shared\Transfer\BenefitVoucherDealDataTransfer;
use Generated\Shared\Transfer\CustomerBalanceByCurrencyTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShoppingPointsDealTransfer;
use Pyz\Shared\MyWorldPayment\MyWorldPaymentConfig as SharedMyWorldPaymentConfig;
use Pyz\Zed\Customer\CustomerDependencyProvider;
use Pyz\Zed\CustomerGroupProductList\Communication\Plugin\Customer\CustomerGroupProductListCustomerTransferExpanderPlugin;
use Pyz\Zed\MyWorldPayment\MyWorldPaymentConfig;
use Spryker\Shared\Nopayment\NopaymentConfig;
use Spryker\Shared\PriceProduct\PriceProductConfig;
use Spryker\Zed\AvailabilityNotification\Communication\Plugin\Customer\AvailabilityNotificationSubscriptionCustomerTransferExpanderPlugin;
use Spryker\Zed\Calculation\Business\CalculationFacadeInterface;
use Spryker\Zed\CustomerUserConnector\Communication\Plugin\CustomerTransferUsernameExpanderPlugin;
use Traversable;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Zed
 * @group Calculation
 * @group Business
 * @group Facade
 * @group MyWorldPaymentsCalculationFacadeTest
 * Add your own group annotations below this line
 */
class MyWorldPaymentsCalculationFacadeTest extends Unit
{
    /**
     * @var \PyzTest\Zed\Calculation\CalculationBusinessTester
     */
    protected $tester;

    /**
     * @var \Spryker\Zed\Calculation\Business\CalculationFacadeInterface
     */
    private $facade;

    /**
     * @return void
     */
    protected function _before(): void
    {
        $this->facade = $this->getFacade();

        $this->tester->setDependency(
            CustomerDependencyProvider::PLUGINS_CUSTOMER_TRANSFER_EXPANDER,
            [
                new CustomerTransferUsernameExpanderPlugin(),
                new AvailabilityNotificationSubscriptionCustomerTransferExpanderPlugin(),
                new CustomerGroupProductListCustomerTransferExpanderPlugin(),
            ]
        );
    }

    /**
     * @return void
     */
    public function testQuoteShoppingPointsCalculatedCorrectly(): void
    {
        $items = [
            $this->buildItemTransfer($this->getItemWithShoppingPointsFixtureData()),
            $this->buildItemTransfer($this->getCleanItemFixtureData()),
        ];

        $customerSPBalance = $this->buildCustomerBalanceByCurrencyTransfer($this->getShoppingPointBalanceFixtureData());

        $customerTransfer = $this->createCustomer([$customerSPBalance]);
        $quoteTransfer = $this->createQuote($customerTransfer, $items);
        $calculatedQuote = $this->facade->recalculateQuote($quoteTransfer);

        $totals = $calculatedQuote->getTotals();
        self::assertEquals(2050, $totals->getGrandTotal());
        self::assertEquals(2050, $totals->getPriceToPay());
        self::assertEquals(10, $calculatedQuote->getTotalUsedShoppingPointsAmount());
        self::assertEmpty($calculatedQuote->getPayments());
    }

    /**
     * @return void
     */
    public function testQuoteShoppingPointsWithCashbackCalculatedCorrectly(): void
    {
        $items = [
            $this->buildItemTransfer($this->getItemWithShoppingPointsFixtureData()),
            $this->buildItemTransfer($this->getCleanItemFixtureData()),
        ];

        $customerSPBalance = $this->buildCustomerBalanceByCurrencyTransfer($this->getShoppingPointBalanceFixtureData());

        $customerCashbackBalance = $this->buildCustomerBalanceByCurrencyTransfer([
            CustomerBalanceByCurrencyTransfer::EXCHANGE_RATE => 1,
            CustomerBalanceByCurrencyTransfer::AVAILABLE_BALANCE => 5.50,
            CustomerBalanceByCurrencyTransfer::TARGET_AVAILABLE_BALANCE => 5.50,
            CustomerBalanceByCurrencyTransfer::PAYMENT_OPTION_ID => 6,
        ]);

        $customerTransfer = $this->createCustomer([$customerSPBalance, $customerCashbackBalance]);
        $quoteTransfer = $this->createQuote($customerTransfer, $items);
        $quoteTransfer->setUseCashbackBalance(true);
        $calculatedQuote = $this->facade->recalculateQuote($quoteTransfer);

        $totals = $calculatedQuote->getTotals();
        self::assertEquals(2050, $totals->getGrandTotal());
        self::assertEquals(1500, $totals->getPriceToPay());
        self::assertEquals(10, $calculatedQuote->getTotalUsedShoppingPointsAmount());

        $this->assertQuoteHasMyWorldPaymentMethod(
            $calculatedQuote,
            MyWorldPaymentConfig::PAYMENT_METHOD_CASHBACK_NAME,
            550
        );
    }

    /**
     * @return void
     */
    public function testQuoteShoppingPointsWithEVoucherCalculatedCorrectly(): void
    {
        $items = [
            $this->buildItemTransfer($this->getItemWithShoppingPointsFixtureData()),
            $this->buildItemTransfer($this->getCleanItemFixtureData()),
        ];

        $customerSPBalance = $this->buildCustomerBalanceByCurrencyTransfer($this->getShoppingPointBalanceFixtureData());

        $customerEVoucherBalance = $this->buildCustomerBalanceByCurrencyTransfer([
            CustomerBalanceByCurrencyTransfer::EXCHANGE_RATE => 1,
            CustomerBalanceByCurrencyTransfer::AVAILABLE_BALANCE => 5.50,
            CustomerBalanceByCurrencyTransfer::TARGET_AVAILABLE_BALANCE => 5.50,
            CustomerBalanceByCurrencyTransfer::PAYMENT_OPTION_ID => 1,
        ]);

        $customerTransfer = $this->createCustomer([$customerSPBalance, $customerEVoucherBalance]);
        $quoteTransfer = $this->createQuote($customerTransfer, $items);
        $quoteTransfer->setUseEVoucherBalance(true);
        $calculatedQuote = $this->facade->recalculateQuote($quoteTransfer);

        $totals = $calculatedQuote->getTotals();
        self::assertEquals(2050, $totals->getGrandTotal());
        self::assertEquals(1500, $totals->getPriceToPay());
        self::assertEquals(10, $calculatedQuote->getTotalUsedShoppingPointsAmount());

        $this->assertQuoteHasMyWorldPaymentMethod(
            $calculatedQuote,
            MyWorldPaymentConfig::PAYMENT_METHOD_EVOUCHER_NAME,
            550
        );
    }

    /**
     * @return void
     */
    public function testQuoteShoppingPointsWithEVoucherOnBehalfOfMarketerCalculatedCorrectly(): void
    {
        $items = [
            $this->buildItemTransfer($this->getItemWithShoppingPointsFixtureData()),
            $this->buildItemTransfer($this->getCleanItemFixtureData()),
        ];

        $customerSPBalance = $this->buildCustomerBalanceByCurrencyTransfer($this->getShoppingPointBalanceFixtureData());

        $customerMarketerEVoucherBalance = $this->buildCustomerBalanceByCurrencyTransfer([
            CustomerBalanceByCurrencyTransfer::EXCHANGE_RATE => 1,
            CustomerBalanceByCurrencyTransfer::AVAILABLE_BALANCE => 5.50,
            CustomerBalanceByCurrencyTransfer::TARGET_AVAILABLE_BALANCE => 5.50,
            CustomerBalanceByCurrencyTransfer::PAYMENT_OPTION_ID => 2,
        ]);

        $customerTransfer = $this->createCustomer([$customerSPBalance, $customerMarketerEVoucherBalance]);
        $quoteTransfer = $this->createQuote($customerTransfer, $items);
        $quoteTransfer->setUseEVoucherOnBehalfOfMarketer(true);
        $calculatedQuote = $this->facade->recalculateQuote($quoteTransfer);

        $totals = $calculatedQuote->getTotals();
        self::assertEquals(2050, $totals->getGrandTotal());
        self::assertEquals(1500, $totals->getPriceToPay());
        self::assertEquals(10, $calculatedQuote->getTotalUsedShoppingPointsAmount());

        $this->assertQuoteHasMyWorldPaymentMethod(
            $calculatedQuote,
            MyWorldPaymentConfig::PAYMENT_METHOD_EVOUCHER_ON_BEHALF_OF_MARKETER_NAME,
            550
        );
    }

    /**
     * @return void
     */
    public function testQuoteShoppingPointsWithCashbackTotallyCoveringPriceToPay(): void
    {
        $items = [
            $this->buildItemTransfer($this->getItemWithShoppingPointsFixtureData()),
            $this->buildItemTransfer($this->getCleanItemFixtureData()),
        ];

        $customerSPBalance = $this->buildCustomerBalanceByCurrencyTransfer($this->getShoppingPointBalanceFixtureData());

        $customerCashbackBalance = $this->buildCustomerBalanceByCurrencyTransfer([
            CustomerBalanceByCurrencyTransfer::EXCHANGE_RATE => 1,
            CustomerBalanceByCurrencyTransfer::AVAILABLE_BALANCE => 35.50,
            CustomerBalanceByCurrencyTransfer::TARGET_AVAILABLE_BALANCE => 35.50,
            CustomerBalanceByCurrencyTransfer::PAYMENT_OPTION_ID => 6,
        ]);

        $customerTransfer = $this->createCustomer([$customerSPBalance, $customerCashbackBalance]);
        $quoteTransfer = $this->createQuote($customerTransfer, $items);
        $quoteTransfer->setUseCashbackBalance(true);
        $quoteTransfer->setPayment($this->createNoPaymentTransfer());
        $calculatedQuote = $this->facade->recalculateQuote($quoteTransfer);

        $totals = $calculatedQuote->getTotals();
        self::assertEquals(2050, $totals->getGrandTotal());
        self::assertEquals(0, $totals->getPriceToPay());
        self::assertEquals(10, $calculatedQuote->getTotalUsedShoppingPointsAmount());

        $this->assertQuoteHasMyWorldPaymentMethod(
            $calculatedQuote,
            MyWorldPaymentConfig::PAYMENT_METHOD_CASHBACK_NAME,
            2050
        );
    }

    /**
     * @return void
     */
    public function testQuoteShoppingPointsWithEVoucherTotallyCoveringPriceToPay(): void
    {
        $items = [
            $this->buildItemTransfer($this->getItemWithShoppingPointsFixtureData()),
            $this->buildItemTransfer($this->getCleanItemFixtureData()),
        ];

        $customerSPBalance = $this->buildCustomerBalanceByCurrencyTransfer($this->getShoppingPointBalanceFixtureData());

        $customerEVoucherBalance = $this->buildCustomerBalanceByCurrencyTransfer([
            CustomerBalanceByCurrencyTransfer::EXCHANGE_RATE => 1,
            CustomerBalanceByCurrencyTransfer::AVAILABLE_BALANCE => 35.50,
            CustomerBalanceByCurrencyTransfer::TARGET_AVAILABLE_BALANCE => 35.50,
            CustomerBalanceByCurrencyTransfer::PAYMENT_OPTION_ID => 1,
        ]);

        $customerTransfer = $this->createCustomer([$customerSPBalance, $customerEVoucherBalance]);
        $quoteTransfer = $this->createQuote($customerTransfer, $items);
        $quoteTransfer->setUseEVoucherBalance(true);
        $quoteTransfer->setPayment($this->createNoPaymentTransfer());
        $calculatedQuote = $this->facade->recalculateQuote($quoteTransfer);

        $totals = $calculatedQuote->getTotals();
        self::assertEquals(2050, $totals->getGrandTotal());
        self::assertEquals(0, $totals->getPriceToPay());
        self::assertEquals(10, $calculatedQuote->getTotalUsedShoppingPointsAmount());

        $this->assertQuoteHasMyWorldPaymentMethod(
            $calculatedQuote,
            MyWorldPaymentConfig::PAYMENT_METHOD_EVOUCHER_NAME,
            2050
        );
    }

    /**
     * @return void
     */
    public function testQuoteShoppingPointsWithEVoucherOnBehalfOfMarketerTotallyCoveringPriceToPay(): void
    {
        $items = [
            $this->buildItemTransfer($this->getItemWithShoppingPointsFixtureData()),
            $this->buildItemTransfer($this->getCleanItemFixtureData()),
        ];

        $customerSPBalance = $this->buildCustomerBalanceByCurrencyTransfer($this->getShoppingPointBalanceFixtureData());

        $customerMarketerEVoucherBalance = $this->buildCustomerBalanceByCurrencyTransfer([
            CustomerBalanceByCurrencyTransfer::EXCHANGE_RATE => 1,
            CustomerBalanceByCurrencyTransfer::AVAILABLE_BALANCE => 35.50,
            CustomerBalanceByCurrencyTransfer::TARGET_AVAILABLE_BALANCE => 35.50,
            CustomerBalanceByCurrencyTransfer::PAYMENT_OPTION_ID => 2,
        ]);

        $customerTransfer = $this->createCustomer([$customerSPBalance, $customerMarketerEVoucherBalance]);
        $quoteTransfer = $this->createQuote($customerTransfer, $items);
        $quoteTransfer->setUseEVoucherOnBehalfOfMarketer(true);
        $quoteTransfer->setPayment($this->createNoPaymentTransfer());
        $calculatedQuote = $this->facade->recalculateQuote($quoteTransfer);

        $totals = $calculatedQuote->getTotals();
        self::assertEquals(2050, $totals->getGrandTotal());
        self::assertEquals(0, $totals->getPriceToPay());
        self::assertEquals(10, $calculatedQuote->getTotalUsedShoppingPointsAmount());

        $this->assertQuoteHasMyWorldPaymentMethod(
            $calculatedQuote,
            MyWorldPaymentConfig::PAYMENT_METHOD_EVOUCHER_ON_BEHALF_OF_MARKETER_NAME,
            2050
        );
    }

    /**
     * @group testQuoteBenefitVouchersCalculatedCorrectly
     *
     * @return void
     */
    public function testQuoteBenefitVouchersCalculatedCorrectly(): void
    {
        $items = [
            $this->buildItemTransfer($this->getItemWithBenefitVoucherFixtureData()),
            $this->buildItemTransfer($this->getCleanItemFixtureData()),
        ];

        $customerBVBalance = $this->buildCustomerBalanceByCurrencyTransfer($this->getBenefitVoucherBalanceFixtureData());

        $customerTransfer = $this->createCustomer([$customerBVBalance]);
        $quoteTransfer = $this->createQuote($customerTransfer, $items);
        $calculatedQuote = $this->facade->recalculateQuote($quoteTransfer);

        $totals = $calculatedQuote->getTotals();
        self::assertEquals(2450, $totals->getGrandTotal());
        self::assertEquals(2050, $totals->getPriceToPay());

        $this->assertQuoteHasMyWorldPaymentMethod(
            $calculatedQuote,
            MyWorldPaymentConfig::PAYMENT_METHOD_BENEFIT_VOUCHER_NAME,
            400
        );
        self::assertEquals(400, $calculatedQuote->getTotalUsedBenefitVouchersAmount());
    }

    /**
     * @return void
     */
    public function testQuoteBenefitVouchersAndShoppingPointsCalculatedCorrectly(): void
    {
        $items = [
            $this->buildItemTransfer($this->getItemWithBenefitVoucherFixtureData()),
            $this->buildItemTransfer($this->getItemWithShoppingPointsFixtureData()),
            $this->buildItemTransfer($this->getCleanItemFixtureData()),
        ];

        $customerBVBalance = $this->buildCustomerBalanceByCurrencyTransfer($this->getBenefitVoucherBalanceFixtureData());
        $customerSPBalance = $this->buildCustomerBalanceByCurrencyTransfer($this->getShoppingPointBalanceFixtureData());

        $customerTransfer = $this->createCustomer([$customerBVBalance, $customerSPBalance]);
        $quoteTransfer = $this->createQuote($customerTransfer, $items);
        $calculatedQuote = $this->facade->recalculateQuote($quoteTransfer);

        $totals = $calculatedQuote->getTotals();
        self::assertEquals(4050, $totals->getGrandTotal());
        self::assertEquals(3650, $totals->getPriceToPay());

        $this->assertQuoteHasMyWorldPaymentMethod(
            $calculatedQuote,
            MyWorldPaymentConfig::PAYMENT_METHOD_BENEFIT_VOUCHER_NAME,
            400
        );
        self::assertEquals(400, $calculatedQuote->getTotalUsedBenefitVouchersAmount());
        self::assertEquals(10, $calculatedQuote->getTotalUsedShoppingPointsAmount());
    }

    /**
     * @return void
     */
    public function testQuoteBenefitVouchersAndShoppingPointsWithEVoucherPaymentCalculation(): void
    {
        $items = [
            $this->buildItemTransfer($this->getItemWithBenefitVoucherFixtureData()),
            $this->buildItemTransfer($this->getItemWithShoppingPointsFixtureData()),
            $this->buildItemTransfer($this->getCleanItemFixtureData()),
        ];

        $customerBVBalance = $this->buildCustomerBalanceByCurrencyTransfer($this->getBenefitVoucherBalanceFixtureData());
        $customerSPBalance = $this->buildCustomerBalanceByCurrencyTransfer($this->getShoppingPointBalanceFixtureData());
        $customerEVoucherBalance = $this->buildCustomerBalanceByCurrencyTransfer([
            CustomerBalanceByCurrencyTransfer::EXCHANGE_RATE => 1,
            CustomerBalanceByCurrencyTransfer::AVAILABLE_BALANCE => 5.50,
            CustomerBalanceByCurrencyTransfer::TARGET_AVAILABLE_BALANCE => 5.50,
            CustomerBalanceByCurrencyTransfer::PAYMENT_OPTION_ID => 1,
        ]);

        $customerTransfer = $this->createCustomer([$customerBVBalance, $customerSPBalance, $customerEVoucherBalance]);
        $quoteTransfer = $this->createQuote($customerTransfer, $items);
        $quoteTransfer->setUseEVoucherBalance(true);
        $calculatedQuote = $this->facade->recalculateQuote($quoteTransfer);

        $totals = $calculatedQuote->getTotals();
        self::assertEquals(4050, $totals->getGrandTotal());
        self::assertEquals(3100, $totals->getPriceToPay());

        $this->assertQuoteHasMyWorldPaymentMethod(
            $calculatedQuote,
            MyWorldPaymentConfig::PAYMENT_METHOD_BENEFIT_VOUCHER_NAME,
            400
        );

        $this->assertQuoteHasMyWorldPaymentMethod(
            $calculatedQuote,
            MyWorldPaymentConfig::PAYMENT_METHOD_EVOUCHER_NAME,
            550
        );
        self::assertEquals(400, $calculatedQuote->getTotalUsedBenefitVouchersAmount());
        self::assertEquals(10, $calculatedQuote->getTotalUsedShoppingPointsAmount());
    }

    /**
     * @return void
     */
    public function testQuoteBenefitVouchersAndShoppingPointsWithEVoucherOnBehalfOfMarketerPaymentCalculation(): void
    {
        $items = [
            $this->buildItemTransfer($this->getItemWithBenefitVoucherFixtureData()),
            $this->buildItemTransfer($this->getItemWithShoppingPointsFixtureData()),
            $this->buildItemTransfer($this->getCleanItemFixtureData()),
        ];

        $customerBVBalance = $this->buildCustomerBalanceByCurrencyTransfer($this->getBenefitVoucherBalanceFixtureData());
        $customerSPBalance = $this->buildCustomerBalanceByCurrencyTransfer($this->getShoppingPointBalanceFixtureData());
        $customerMarketerEVoucherBalance = $this->buildCustomerBalanceByCurrencyTransfer([
            CustomerBalanceByCurrencyTransfer::EXCHANGE_RATE => 1,
            CustomerBalanceByCurrencyTransfer::AVAILABLE_BALANCE => 5.50,
            CustomerBalanceByCurrencyTransfer::TARGET_AVAILABLE_BALANCE => 5.50,
            CustomerBalanceByCurrencyTransfer::PAYMENT_OPTION_ID => 2,
        ]);

        $customerTransfer = $this->createCustomer([$customerBVBalance, $customerSPBalance, $customerMarketerEVoucherBalance]);
        $quoteTransfer = $this->createQuote($customerTransfer, $items);
        $quoteTransfer->setUseEVoucherOnBehalfOfMarketer(true);
        $calculatedQuote = $this->facade->recalculateQuote($quoteTransfer);

        $totals = $calculatedQuote->getTotals();
        self::assertEquals(4050, $totals->getGrandTotal());
        self::assertEquals(3100, $totals->getPriceToPay());

        $this->assertQuoteHasMyWorldPaymentMethod(
            $calculatedQuote,
            MyWorldPaymentConfig::PAYMENT_METHOD_BENEFIT_VOUCHER_NAME,
            400
        );

        $this->assertQuoteHasMyWorldPaymentMethod(
            $calculatedQuote,
            MyWorldPaymentConfig::PAYMENT_METHOD_EVOUCHER_ON_BEHALF_OF_MARKETER_NAME,
            550
        );
        self::assertEquals(400, $calculatedQuote->getTotalUsedBenefitVouchersAmount());
        self::assertEquals(10, $calculatedQuote->getTotalUsedShoppingPointsAmount());
    }

    /**
     * @return void
     */
    public function testQuoteBenefitVouchersAndShoppingPointsWithCashbackPaymentCalculation(): void
    {
        $items = [
            $this->buildItemTransfer($this->getItemWithBenefitVoucherFixtureData()),
            $this->buildItemTransfer($this->getItemWithShoppingPointsFixtureData()),
            $this->buildItemTransfer($this->getCleanItemFixtureData()),
        ];

        $customerBVBalance = $this->buildCustomerBalanceByCurrencyTransfer($this->getBenefitVoucherBalanceFixtureData());
        $customerSPBalance = $this->buildCustomerBalanceByCurrencyTransfer($this->getShoppingPointBalanceFixtureData());
        $customerCashbackBalance = $this->buildCustomerBalanceByCurrencyTransfer([
            CustomerBalanceByCurrencyTransfer::EXCHANGE_RATE => 1,
            CustomerBalanceByCurrencyTransfer::AVAILABLE_BALANCE => 5.50,
            CustomerBalanceByCurrencyTransfer::TARGET_AVAILABLE_BALANCE => 5.50,
            CustomerBalanceByCurrencyTransfer::PAYMENT_OPTION_ID => 6,
        ]);

        $customerTransfer = $this->createCustomer([$customerBVBalance, $customerSPBalance, $customerCashbackBalance]);
        $quoteTransfer = $this->createQuote($customerTransfer, $items);
        $quoteTransfer->setUseCashbackBalance(true);
        $calculatedQuote = $this->facade->recalculateQuote($quoteTransfer);

        $totals = $calculatedQuote->getTotals();
        self::assertEquals(4050, $totals->getGrandTotal());
        self::assertEquals(3100, $totals->getPriceToPay());

        $this->assertQuoteHasMyWorldPaymentMethod(
            $calculatedQuote,
            MyWorldPaymentConfig::PAYMENT_METHOD_BENEFIT_VOUCHER_NAME,
            400
        );

        $this->assertQuoteHasMyWorldPaymentMethod(
            $calculatedQuote,
            MyWorldPaymentConfig::PAYMENT_METHOD_CASHBACK_NAME,
            550
        );
        self::assertEquals(400, $calculatedQuote->getTotalUsedBenefitVouchersAmount());
        self::assertEquals(10, $calculatedQuote->getTotalUsedShoppingPointsAmount());
    }

    /**
     * @return void
     */
    public function testQuoteBenefitVouchersAndShoppingPointsWithCashbackCoveringTotalPriceToPay(): void
    {
        $items = [
            $this->buildItemTransfer($this->getItemWithBenefitVoucherFixtureData()),
            $this->buildItemTransfer($this->getItemWithShoppingPointsFixtureData()),
            $this->buildItemTransfer($this->getCleanItemFixtureData()),
        ];

        $customerBVBalance = $this->buildCustomerBalanceByCurrencyTransfer($this->getBenefitVoucherBalanceFixtureData());
        $customerSPBalance = $this->buildCustomerBalanceByCurrencyTransfer($this->getShoppingPointBalanceFixtureData());
        $customerCashbackBalance = $this->buildCustomerBalanceByCurrencyTransfer([
            CustomerBalanceByCurrencyTransfer::EXCHANGE_RATE => 1,
            CustomerBalanceByCurrencyTransfer::AVAILABLE_BALANCE => 40.50,
            CustomerBalanceByCurrencyTransfer::TARGET_AVAILABLE_BALANCE => 40.50,
            CustomerBalanceByCurrencyTransfer::PAYMENT_OPTION_ID => 6,
        ]);

        $customerTransfer = $this->createCustomer([$customerBVBalance, $customerSPBalance, $customerCashbackBalance]);
        $quoteTransfer = $this->createQuote($customerTransfer, $items);
        $quoteTransfer->setUseCashbackBalance(true);
        $quoteTransfer->setPayment($this->createNoPaymentTransfer());
        $calculatedQuote = $this->facade->recalculateQuote($quoteTransfer);

        $totals = $calculatedQuote->getTotals();
        self::assertEquals(4050, $totals->getGrandTotal());
        self::assertEquals(0, $totals->getPriceToPay());

        $this->assertQuoteHasMyWorldPaymentMethod(
            $calculatedQuote,
            MyWorldPaymentConfig::PAYMENT_METHOD_BENEFIT_VOUCHER_NAME,
            400
        );

        $this->assertQuoteHasMyWorldPaymentMethod(
            $calculatedQuote,
            MyWorldPaymentConfig::PAYMENT_METHOD_CASHBACK_NAME,
            3650
        );
        self::assertEquals(400, $calculatedQuote->getTotalUsedBenefitVouchersAmount());
        self::assertEquals(10, $calculatedQuote->getTotalUsedShoppingPointsAmount());
    }

    /**
     * @return void
     */
    public function testQuoteBenefitVouchersAndShoppingPointsWithEVoucherCoveringTotalPriceToPay(): void
    {
        $items = [
            $this->buildItemTransfer($this->getItemWithBenefitVoucherFixtureData()),
            $this->buildItemTransfer($this->getItemWithShoppingPointsFixtureData()),
            $this->buildItemTransfer($this->getCleanItemFixtureData()),
        ];

        $customerBVBalance = $this->buildCustomerBalanceByCurrencyTransfer($this->getBenefitVoucherBalanceFixtureData());
        $customerSPBalance = $this->buildCustomerBalanceByCurrencyTransfer($this->getShoppingPointBalanceFixtureData());
        $customerEVoucherBalance = $this->buildCustomerBalanceByCurrencyTransfer([
            CustomerBalanceByCurrencyTransfer::EXCHANGE_RATE => 1,
            CustomerBalanceByCurrencyTransfer::AVAILABLE_BALANCE => 40.50,
            CustomerBalanceByCurrencyTransfer::TARGET_AVAILABLE_BALANCE => 40.50,
            CustomerBalanceByCurrencyTransfer::PAYMENT_OPTION_ID => 1,
        ]);

        $customerTransfer = $this->createCustomer([$customerBVBalance, $customerSPBalance, $customerEVoucherBalance]);
        $quoteTransfer = $this->createQuote($customerTransfer, $items);
        $quoteTransfer->setUseEVoucherBalance(true);
        $quoteTransfer->setPayment($this->createNoPaymentTransfer());
        $calculatedQuote = $this->facade->recalculateQuote($quoteTransfer);

        $totals = $calculatedQuote->getTotals();
        self::assertEquals(4050, $totals->getGrandTotal());
        self::assertEquals(0, $totals->getPriceToPay());

        $this->assertQuoteHasMyWorldPaymentMethod(
            $calculatedQuote,
            MyWorldPaymentConfig::PAYMENT_METHOD_BENEFIT_VOUCHER_NAME,
            400
        );

        $this->assertQuoteHasMyWorldPaymentMethod(
            $calculatedQuote,
            MyWorldPaymentConfig::PAYMENT_METHOD_EVOUCHER_NAME,
            3650
        );
        self::assertEquals(400, $calculatedQuote->getTotalUsedBenefitVouchersAmount());
        self::assertEquals(10, $calculatedQuote->getTotalUsedShoppingPointsAmount());
    }

    /**
     * @return void
     */
    public function testQuoteBenefitVouchersAndShoppingPointsWithEVoucherOnBehalfOfMarketerCoveringTotalPriceToPay(): void
    {
        $items = [
            $this->buildItemTransfer($this->getItemWithBenefitVoucherFixtureData()),
            $this->buildItemTransfer($this->getItemWithShoppingPointsFixtureData()),
            $this->buildItemTransfer($this->getCleanItemFixtureData()),
        ];

        $customerBVBalance = $this->buildCustomerBalanceByCurrencyTransfer($this->getBenefitVoucherBalanceFixtureData());
        $customerSPBalance = $this->buildCustomerBalanceByCurrencyTransfer($this->getShoppingPointBalanceFixtureData());
        $customerMarketerEVoucherBalance = $this->buildCustomerBalanceByCurrencyTransfer([
            CustomerBalanceByCurrencyTransfer::EXCHANGE_RATE => 1,
            CustomerBalanceByCurrencyTransfer::AVAILABLE_BALANCE => 40.50,
            CustomerBalanceByCurrencyTransfer::TARGET_AVAILABLE_BALANCE => 40.50,
            CustomerBalanceByCurrencyTransfer::PAYMENT_OPTION_ID => 2,
        ]);

        $customerTransfer = $this->createCustomer([$customerBVBalance, $customerSPBalance, $customerMarketerEVoucherBalance]);
        $quoteTransfer = $this->createQuote($customerTransfer, $items);
        $quoteTransfer->setUseEVoucherOnBehalfOfMarketer(true);
        $quoteTransfer->setPayment($this->createNoPaymentTransfer());
        $calculatedQuote = $this->facade->recalculateQuote($quoteTransfer);

        $totals = $calculatedQuote->getTotals();
        self::assertEquals(4050, $totals->getGrandTotal());
        self::assertEquals(0, $totals->getPriceToPay());

        $this->assertQuoteHasMyWorldPaymentMethod(
            $calculatedQuote,
            MyWorldPaymentConfig::PAYMENT_METHOD_BENEFIT_VOUCHER_NAME,
            400
        );

        $this->assertQuoteHasMyWorldPaymentMethod(
            $calculatedQuote,
            MyWorldPaymentConfig::PAYMENT_METHOD_EVOUCHER_ON_BEHALF_OF_MARKETER_NAME,
            3650
        );
        self::assertEquals(400, $calculatedQuote->getTotalUsedBenefitVouchersAmount());
        self::assertEquals(10, $calculatedQuote->getTotalUsedShoppingPointsAmount());
    }

    /**
     * @return void
     */
    public function testQuoteBenefitVouchersWithEVoucherCalculatedCorrectly(): void
    {
        $items = [
            $this->buildItemTransfer($this->getItemWithBenefitVoucherFixtureData()),
            $this->buildItemTransfer($this->getCleanItemFixtureData()),
        ];

        $customerBVBalance = $this->buildCustomerBalanceByCurrencyTransfer($this->getBenefitVoucherBalanceFixtureData());
        $customerEVoucherBalance = $this->buildCustomerBalanceByCurrencyTransfer([
            CustomerBalanceByCurrencyTransfer::EXCHANGE_RATE => 1,
            CustomerBalanceByCurrencyTransfer::AVAILABLE_BALANCE => 5.50,
            CustomerBalanceByCurrencyTransfer::TARGET_AVAILABLE_BALANCE => 5.50,
            CustomerBalanceByCurrencyTransfer::PAYMENT_OPTION_ID => 1,
        ]);

        $customerTransfer = $this->createCustomer([$customerBVBalance, $customerEVoucherBalance]);
        $quoteTransfer = $this->createQuote($customerTransfer, $items);
        $quoteTransfer->setUseEVoucherBalance(true);
        $calculatedQuote = $this->facade->recalculateQuote($quoteTransfer);

        $totals = $calculatedQuote->getTotals();
        self::assertEquals(2450, $totals->getGrandTotal());
        self::assertEquals(1500, $totals->getPriceToPay());

        $this->assertQuoteHasMyWorldPaymentMethod(
            $calculatedQuote,
            MyWorldPaymentConfig::PAYMENT_METHOD_BENEFIT_VOUCHER_NAME,
            400
        );
        $this->assertQuoteHasMyWorldPaymentMethod(
            $calculatedQuote,
            MyWorldPaymentConfig::PAYMENT_METHOD_EVOUCHER_NAME,
            550
        );
        self::assertEquals(400, $calculatedQuote->getTotalUsedBenefitVouchersAmount());
    }

    /**
     * @return void
     */
    public function testQuoteBenefitVouchersWithEVoucherOnBehalfOfMarketerCalculatedCorrectly(): void
    {
        $items = [
            $this->buildItemTransfer($this->getItemWithBenefitVoucherFixtureData()),
            $this->buildItemTransfer($this->getCleanItemFixtureData()),
        ];

        $customerBVBalance = $this->buildCustomerBalanceByCurrencyTransfer($this->getBenefitVoucherBalanceFixtureData());
        $customerMarketerEVoucherBalance = $this->buildCustomerBalanceByCurrencyTransfer([
            CustomerBalanceByCurrencyTransfer::EXCHANGE_RATE => 1,
            CustomerBalanceByCurrencyTransfer::AVAILABLE_BALANCE => 5.50,
            CustomerBalanceByCurrencyTransfer::TARGET_AVAILABLE_BALANCE => 5.50,
            CustomerBalanceByCurrencyTransfer::PAYMENT_OPTION_ID => 2,
        ]);

        $customerTransfer = $this->createCustomer([$customerBVBalance, $customerMarketerEVoucherBalance]);
        $quoteTransfer = $this->createQuote($customerTransfer, $items);
        $quoteTransfer->setUseEVoucherOnBehalfOfMarketer(true);
        $calculatedQuote = $this->facade->recalculateQuote($quoteTransfer);

        $totals = $calculatedQuote->getTotals();
        self::assertEquals(2450, $totals->getGrandTotal());
        self::assertEquals(1500, $totals->getPriceToPay());

        $this->assertQuoteHasMyWorldPaymentMethod(
            $calculatedQuote,
            MyWorldPaymentConfig::PAYMENT_METHOD_BENEFIT_VOUCHER_NAME,
            400
        );
        $this->assertQuoteHasMyWorldPaymentMethod(
            $calculatedQuote,
            MyWorldPaymentConfig::PAYMENT_METHOD_EVOUCHER_ON_BEHALF_OF_MARKETER_NAME,
            550
        );
        self::assertEquals(400, $calculatedQuote->getTotalUsedBenefitVouchersAmount());
    }

    /**
     * @return void
     */
    public function testQuoteBenefitVouchersWithCashbackCalculatedCorrectly(): void
    {
        $items = [
            $this->buildItemTransfer($this->getItemWithBenefitVoucherFixtureData()),
            $this->buildItemTransfer($this->getCleanItemFixtureData()),
        ];

        $customerBVBalance = $this->buildCustomerBalanceByCurrencyTransfer($this->getBenefitVoucherBalanceFixtureData());
        $customerCashbackBalance = $this->buildCustomerBalanceByCurrencyTransfer([
            CustomerBalanceByCurrencyTransfer::EXCHANGE_RATE => 1,
            CustomerBalanceByCurrencyTransfer::AVAILABLE_BALANCE => 5.50,
            CustomerBalanceByCurrencyTransfer::TARGET_AVAILABLE_BALANCE => 5.50,
            CustomerBalanceByCurrencyTransfer::PAYMENT_OPTION_ID => 6,
        ]);

        $customerTransfer = $this->createCustomer([$customerBVBalance, $customerCashbackBalance]);
        $quoteTransfer = $this->createQuote($customerTransfer, $items);
        $quoteTransfer->setUseCashbackBalance(true);
        $calculatedQuote = $this->facade->recalculateQuote($quoteTransfer);

        $totals = $calculatedQuote->getTotals();
        self::assertEquals(2450, $totals->getGrandTotal());
        self::assertEquals(1500, $totals->getPriceToPay());

        $this->assertQuoteHasMyWorldPaymentMethod(
            $calculatedQuote,
            MyWorldPaymentConfig::PAYMENT_METHOD_BENEFIT_VOUCHER_NAME,
            400
        );
        $this->assertQuoteHasMyWorldPaymentMethod(
            $calculatedQuote,
            MyWorldPaymentConfig::PAYMENT_METHOD_CASHBACK_NAME,
            550
        );
        self::assertEquals(400, $calculatedQuote->getTotalUsedBenefitVouchersAmount());
    }

    /**
     * @return void
     */
    public function testQuoteBenefitVouchersWithCashbackTotallyCoveringPriceToPay(): void
    {
        $items = [
            $this->buildItemTransfer($this->getItemWithBenefitVoucherFixtureData()),
            $this->buildItemTransfer($this->getCleanItemFixtureData()),
        ];

        $customerBVBalance = $this->buildCustomerBalanceByCurrencyTransfer($this->getBenefitVoucherBalanceFixtureData());
        $customerCashbackBalance = $this->buildCustomerBalanceByCurrencyTransfer([
            CustomerBalanceByCurrencyTransfer::EXCHANGE_RATE => 1,
            CustomerBalanceByCurrencyTransfer::AVAILABLE_BALANCE => 35.50,
            CustomerBalanceByCurrencyTransfer::TARGET_AVAILABLE_BALANCE => 35.50,
            CustomerBalanceByCurrencyTransfer::PAYMENT_OPTION_ID => 6,
        ]);

        $customerTransfer = $this->createCustomer([$customerBVBalance, $customerCashbackBalance]);
        $quoteTransfer = $this->createQuote($customerTransfer, $items);
        $quoteTransfer->setUseCashbackBalance(true);
        $quoteTransfer->setPayment($this->createNoPaymentTransfer());
        $calculatedQuote = $this->facade->recalculateQuote($quoteTransfer);

        $totals = $calculatedQuote->getTotals();
        self::assertEquals(2450, $totals->getGrandTotal());
        self::assertEquals(0, $totals->getPriceToPay());

        $this->assertQuoteHasMyWorldPaymentMethod(
            $calculatedQuote,
            MyWorldPaymentConfig::PAYMENT_METHOD_BENEFIT_VOUCHER_NAME,
            400
        );
        $this->assertQuoteHasMyWorldPaymentMethod(
            $calculatedQuote,
            MyWorldPaymentConfig::PAYMENT_METHOD_CASHBACK_NAME,
            2050
        );
        self::assertEquals(400, $calculatedQuote->getTotalUsedBenefitVouchersAmount());
    }

    /**
     * @return void
     */
    public function testQuoteBenefitVouchersWithEVoucherTotallyCoveringPriceToPay(): void
    {
        $items = [
            $this->buildItemTransfer($this->getItemWithBenefitVoucherFixtureData()),
            $this->buildItemTransfer($this->getCleanItemFixtureData()),
        ];

        $customerBVBalance = $this->buildCustomerBalanceByCurrencyTransfer($this->getBenefitVoucherBalanceFixtureData());
        $customerEVoucherBalance = $this->buildCustomerBalanceByCurrencyTransfer([
            CustomerBalanceByCurrencyTransfer::EXCHANGE_RATE => 1,
            CustomerBalanceByCurrencyTransfer::AVAILABLE_BALANCE => 35.50,
            CustomerBalanceByCurrencyTransfer::TARGET_AVAILABLE_BALANCE => 35.50,
            CustomerBalanceByCurrencyTransfer::PAYMENT_OPTION_ID => 1,
        ]);

        $customerTransfer = $this->createCustomer([$customerBVBalance, $customerEVoucherBalance]);
        $quoteTransfer = $this->createQuote($customerTransfer, $items);
        $quoteTransfer->setUseEVoucherBalance(true);
        $quoteTransfer->setPayment($this->createNoPaymentTransfer());
        $calculatedQuote = $this->facade->recalculateQuote($quoteTransfer);

        $totals = $calculatedQuote->getTotals();
        self::assertEquals(2450, $totals->getGrandTotal());
        self::assertEquals(0, $totals->getPriceToPay());

        $this->assertQuoteHasMyWorldPaymentMethod(
            $calculatedQuote,
            MyWorldPaymentConfig::PAYMENT_METHOD_BENEFIT_VOUCHER_NAME,
            400
        );
        $this->assertQuoteHasMyWorldPaymentMethod(
            $calculatedQuote,
            MyWorldPaymentConfig::PAYMENT_METHOD_EVOUCHER_NAME,
            2050
        );
        self::assertEquals(400, $calculatedQuote->getTotalUsedBenefitVouchersAmount());
    }

    /**
     * @return void
     */
    public function testQuoteBenefitVouchersWithEVoucherOnBehalfOfMarketerTotallyCoveringPriceToPay(): void
    {
        $items = [
            $this->buildItemTransfer($this->getItemWithBenefitVoucherFixtureData()),
            $this->buildItemTransfer($this->getCleanItemFixtureData()),
        ];

        $customerBVBalance = $this->buildCustomerBalanceByCurrencyTransfer($this->getBenefitVoucherBalanceFixtureData());
        $customerMarketerEVoucherBalance = $this->buildCustomerBalanceByCurrencyTransfer([
            CustomerBalanceByCurrencyTransfer::EXCHANGE_RATE => 1,
            CustomerBalanceByCurrencyTransfer::AVAILABLE_BALANCE => 35.50,
            CustomerBalanceByCurrencyTransfer::TARGET_AVAILABLE_BALANCE => 35.50,
            CustomerBalanceByCurrencyTransfer::PAYMENT_OPTION_ID => 2,
        ]);

        $customerTransfer = $this->createCustomer([$customerBVBalance, $customerMarketerEVoucherBalance]);
        $quoteTransfer = $this->createQuote($customerTransfer, $items);
        $quoteTransfer->setUseEVoucherOnBehalfOfMarketer(true);
        $quoteTransfer->setPayment($this->createNoPaymentTransfer());
        $calculatedQuote = $this->facade->recalculateQuote($quoteTransfer);

        $totals = $calculatedQuote->getTotals();
        self::assertEquals(2450, $totals->getGrandTotal());
        self::assertEquals(0, $totals->getPriceToPay());

        $this->assertQuoteHasMyWorldPaymentMethod(
            $calculatedQuote,
            MyWorldPaymentConfig::PAYMENT_METHOD_BENEFIT_VOUCHER_NAME,
            400
        );
        $this->assertQuoteHasMyWorldPaymentMethod(
            $calculatedQuote,
            MyWorldPaymentConfig::PAYMENT_METHOD_EVOUCHER_ON_BEHALF_OF_MARKETER_NAME,
            2050
        );
        self::assertEquals(400, $calculatedQuote->getTotalUsedBenefitVouchersAmount());
    }

    /**
     * @return void
     */
    public function testQuoteWithEVoucherPayment(): void
    {
        $items = [
            $this->buildItemTransfer($this->getCleanItemFixtureData()),
            $this->buildItemTransfer($this->getCleanItemFixtureData()),
        ];

        $customerEVoucherBalance = $this->buildCustomerBalanceByCurrencyTransfer([
            CustomerBalanceByCurrencyTransfer::EXCHANGE_RATE => 1,
            CustomerBalanceByCurrencyTransfer::AVAILABLE_BALANCE => 2.50,
            CustomerBalanceByCurrencyTransfer::TARGET_AVAILABLE_BALANCE => 2.50,
            CustomerBalanceByCurrencyTransfer::PAYMENT_OPTION_ID => 1,
        ]);

        $customerTransfer = $this->createCustomer([$customerEVoucherBalance]);
        $quoteTransfer = $this->createQuote($customerTransfer, $items);
        $quoteTransfer->setUseEVoucherBalance(true);
        $calculatedQuote = $this->facade->recalculateQuote($quoteTransfer);

        $totals = $calculatedQuote->getTotals();
        self::assertEquals(900, $totals->getGrandTotal());
        self::assertEquals(650, $totals->getPriceToPay());

        $this->assertQuoteHasMyWorldPaymentMethod(
            $calculatedQuote,
            MyWorldPaymentConfig::PAYMENT_METHOD_EVOUCHER_NAME,
            250
        );
    }

    /**
     * @return void
     */
    public function testQuoteWithEVoucherPaymentTotallyCoveringPriceToPay(): void
    {
        $items = [
            $this->buildItemTransfer($this->getCleanItemFixtureData()),
            $this->buildItemTransfer($this->getCleanItemFixtureData()),
        ];

        $customerEVoucherBalance = $this->buildCustomerBalanceByCurrencyTransfer([
            CustomerBalanceByCurrencyTransfer::EXCHANGE_RATE => 1,
            CustomerBalanceByCurrencyTransfer::AVAILABLE_BALANCE => 9.00,
            CustomerBalanceByCurrencyTransfer::TARGET_AVAILABLE_BALANCE => 9.00,
            CustomerBalanceByCurrencyTransfer::PAYMENT_OPTION_ID => 1,
        ]);

        $customerTransfer = $this->createCustomer([$customerEVoucherBalance]);
        $quoteTransfer = $this->createQuote($customerTransfer, $items);
        $quoteTransfer->setUseEVoucherBalance(true);
        $quoteTransfer->setPayment($this->createNoPaymentTransfer());
        $calculatedQuote = $this->facade->recalculateQuote($quoteTransfer);

        $totals = $calculatedQuote->getTotals();
        self::assertEquals(900, $totals->getGrandTotal());
        self::assertEquals(0, $totals->getPriceToPay());

        $this->assertQuoteHasMyWorldPaymentMethod(
            $calculatedQuote,
            MyWorldPaymentConfig::PAYMENT_METHOD_EVOUCHER_NAME,
            900
        );
    }

    /**
     * @return void
     */
    public function testQuoteWithEVoucherOnBehalfOfMarketerPayment(): void
    {
        $items = [
            $this->buildItemTransfer($this->getCleanItemFixtureData()),
            $this->buildItemTransfer($this->getCleanItemFixtureData()),
        ];

        $customerMarketerEVoucherBalance = $this->buildCustomerBalanceByCurrencyTransfer([
            CustomerBalanceByCurrencyTransfer::EXCHANGE_RATE => 1,
            CustomerBalanceByCurrencyTransfer::AVAILABLE_BALANCE => 2.50,
            CustomerBalanceByCurrencyTransfer::TARGET_AVAILABLE_BALANCE => 2.50,
            CustomerBalanceByCurrencyTransfer::PAYMENT_OPTION_ID => 2,
        ]);

        $customerTransfer = $this->createCustomer([$customerMarketerEVoucherBalance]);
        $quoteTransfer = $this->createQuote($customerTransfer, $items);
        $quoteTransfer->setUseEVoucherOnBehalfOfMarketer(true);
        $calculatedQuote = $this->facade->recalculateQuote($quoteTransfer);

        $totals = $calculatedQuote->getTotals();
        self::assertEquals(900, $totals->getGrandTotal());
        self::assertEquals(650, $totals->getPriceToPay());

        $this->assertQuoteHasMyWorldPaymentMethod(
            $calculatedQuote,
            MyWorldPaymentConfig::PAYMENT_METHOD_EVOUCHER_ON_BEHALF_OF_MARKETER_NAME,
            250
        );
    }

    /**
     * @return void
     */
    public function testQuoteWithEVoucherOnBehalfOfMarketerPaymentTotallyCoveringPriceToPay(): void
    {
        $items = [
            $this->buildItemTransfer($this->getCleanItemFixtureData()),
            $this->buildItemTransfer($this->getCleanItemFixtureData()),
        ];

        $customerMarketerEVoucherBalance = $this->buildCustomerBalanceByCurrencyTransfer([
            CustomerBalanceByCurrencyTransfer::EXCHANGE_RATE => 1,
            CustomerBalanceByCurrencyTransfer::AVAILABLE_BALANCE => 9.00,
            CustomerBalanceByCurrencyTransfer::TARGET_AVAILABLE_BALANCE => 9.00,
            CustomerBalanceByCurrencyTransfer::PAYMENT_OPTION_ID => 2,
        ]);

        $customerTransfer = $this->createCustomer([$customerMarketerEVoucherBalance]);
        $quoteTransfer = $this->createQuote($customerTransfer, $items);
        $quoteTransfer->setUseEVoucherOnBehalfOfMarketer(true);
        $quoteTransfer->setPayment($this->createNoPaymentTransfer());
        $calculatedQuote = $this->facade->recalculateQuote($quoteTransfer);

        $totals = $calculatedQuote->getTotals();
        self::assertEquals(900, $totals->getGrandTotal());
        self::assertEquals(0, $totals->getPriceToPay());

        $this->assertQuoteHasMyWorldPaymentMethod(
            $calculatedQuote,
            MyWorldPaymentConfig::PAYMENT_METHOD_EVOUCHER_ON_BEHALF_OF_MARKETER_NAME,
            900
        );
    }

    /**
     * @return void
     */
    public function testQuoteWithCashbackPayment(): void
    {
        $items = [
            $this->buildItemTransfer($this->getCleanItemFixtureData()),
            $this->buildItemTransfer($this->getCleanItemFixtureData()),
        ];

        $customerCashbackBalance = $this->buildCustomerBalanceByCurrencyTransfer([
            CustomerBalanceByCurrencyTransfer::EXCHANGE_RATE => 1,
            CustomerBalanceByCurrencyTransfer::AVAILABLE_BALANCE => 2.50,
            CustomerBalanceByCurrencyTransfer::TARGET_AVAILABLE_BALANCE => 2.50,
            CustomerBalanceByCurrencyTransfer::PAYMENT_OPTION_ID => 6,
        ]);

        $customerTransfer = $this->createCustomer([$customerCashbackBalance]);
        $quoteTransfer = $this->createQuote($customerTransfer, $items);
        $quoteTransfer->setUseCashbackBalance(true);
        $calculatedQuote = $this->facade->recalculateQuote($quoteTransfer);

        $totals = $calculatedQuote->getTotals();
        self::assertEquals(900, $totals->getGrandTotal());
        self::assertEquals(650, $totals->getPriceToPay());

        $this->assertQuoteHasMyWorldPaymentMethod(
            $calculatedQuote,
            MyWorldPaymentConfig::PAYMENT_METHOD_CASHBACK_NAME,
            250
        );
    }

    /**
     * @return void
     */
    public function testQuoteWithCashbackPaymentTotallyCoveringPriceToPay(): void
    {
        $items = [
            $this->buildItemTransfer($this->getCleanItemFixtureData()),
            $this->buildItemTransfer($this->getCleanItemFixtureData()),
        ];

        $customerCashbackBalance = $this->buildCustomerBalanceByCurrencyTransfer([
            CustomerBalanceByCurrencyTransfer::EXCHANGE_RATE => 1,
            CustomerBalanceByCurrencyTransfer::AVAILABLE_BALANCE => 9.00,
            CustomerBalanceByCurrencyTransfer::TARGET_AVAILABLE_BALANCE => 9.00,
            CustomerBalanceByCurrencyTransfer::PAYMENT_OPTION_ID => 6,
        ]);

        $customerTransfer = $this->createCustomer([$customerCashbackBalance]);
        $quoteTransfer = $this->createQuote($customerTransfer, $items);
        $quoteTransfer->setUseCashbackBalance(true);
        $quoteTransfer->setPayment($this->createNoPaymentTransfer());
        $calculatedQuote = $this->facade->recalculateQuote($quoteTransfer);

        $totals = $calculatedQuote->getTotals();
        self::assertEquals(900, $totals->getGrandTotal());
        self::assertEquals(0, $totals->getPriceToPay());

        $this->assertQuoteHasMyWorldPaymentMethod(
            $calculatedQuote,
            MyWorldPaymentConfig::PAYMENT_METHOD_CASHBACK_NAME,
            900
        );
    }

    /**
     * @param \Traversable|\Generated\Shared\Transfer\PaymentTransfer[] $paymentTransfers
     * @param string $paymentMethodName
     *
     * @return \Generated\Shared\Transfer\PaymentTransfer|null
     */
    private function findPaymentInCollection(Traversable $paymentTransfers, string $paymentMethodName): ?PaymentTransfer
    {
        foreach ($paymentTransfers as $paymentTransfer) {
            if ($paymentTransfer->getPaymentMethodName() === $paymentMethodName) {
                return $paymentTransfer;
            }
        }

        return null;
    }

    /**
     * @return \Generated\Shared\Transfer\PaymentTransfer
     */
    private function createNoPaymentTransfer(): PaymentTransfer
    {
        return $this->buildPaymentTransfer([
            PaymentTransfer::PAYMENT_SELECTION => NopaymentConfig::PAYMENT_PROVIDER_NAME,
            PaymentTransfer::PAYMENT_PROVIDER => NopaymentConfig::PAYMENT_PROVIDER_NAME,
            PaymentTransfer::PAYMENT_METHOD => NopaymentConfig::PAYMENT_METHOD_NAME,
            PaymentTransfer::IS_LIMITED_AMOUNT => true,
            PaymentTransfer::AMOUNT => 0,
        ]);
    }

    /**
     * @param array $paymentOverride
     *
     * @return \Generated\Shared\Transfer\PaymentTransfer
     */
    private function buildPaymentTransfer(array $paymentOverride): PaymentTransfer
    {
        return (new PaymentBuilder($paymentOverride))->build();
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     * @param array $items
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    private function createQuote(CustomerTransfer $customerTransfer, array $items): QuoteTransfer
    {
        $totalUsedBenefitVouchersAmount = array_reduce($items, function (int $carry, ItemTransfer $itemTransfer) {
            $totalBenefitAmount = 0;
            if ($itemTransfer->getBenefitVoucherDealData() !== null) {
                $totalBenefitAmount = $itemTransfer->getBenefitVoucherDealData()->getAmount() * $itemTransfer->getQuantity();
            }

            return $carry + $totalBenefitAmount;
        }, 0);

        return $this->tester->havePersistentQuote([
            QuoteTransfer::CUSTOMER => $customerTransfer->toArray(),
            QuoteTransfer::USE_BENEFIT_VOUCHER => true,
            QuoteTransfer::TOTAL_USED_BENEFIT_VOUCHERS_AMOUNT => $totalUsedBenefitVouchersAmount,
            QuoteTransfer::ITEMS => array_map(function (ItemTransfer $itemTransfer) {
                return $itemTransfer->toArray();
            }, $items),
            QuoteTransfer::PRICE_MODE => PriceProductConfig::PRICE_GROSS_MODE,
        ]);
    }

    /**
     * @param array $balanceOverride
     *
     * @return \Generated\Shared\Transfer\CustomerBalanceByCurrencyTransfer
     */
    private function buildCustomerBalanceByCurrencyTransfer(array $balanceOverride): CustomerBalanceByCurrencyTransfer
    {
        return (new CustomerBalanceByCurrencyBuilder($balanceOverride))->build();
    }

    /**
     * @param array $balances
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    private function createCustomer(array $balances): CustomerTransfer
    {
        return $this->tester->haveCustomer([
            CustomerTransfer::BALANCES => array_map(function (CustomerBalanceByCurrencyTransfer $balance) {
                return $balance->toArray();
            }, $balances),
        ]);
    }

    /**
     * @param array $itemDataOverride
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    private function buildItemTransfer(array $itemDataOverride): ItemTransfer
    {
        return (new ItemBuilder($itemDataOverride))->build();
    }

    /**
     * @return \Spryker\Zed\Calculation\Business\CalculationFacadeInterface
     */
    private function getFacade(): CalculationFacadeInterface
    {
        return $this->tester->getLocator()->calculation()->facade();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $methodName
     * @param int $amount
     *
     * @return void
     */
    private function assertQuoteHasMyWorldPaymentMethod(QuoteTransfer $quoteTransfer, string $methodName, int $amount): void
    {
        $payment = $this->findPaymentInCollection($quoteTransfer->getPayments(), $methodName);
        self::assertNotNull($payment);
        self::assertEquals(
            SharedMyWorldPaymentConfig::PAYMENT_PROVIDER_NAME_MY_WORLD,
            $payment->getPaymentProvider()
        );
        self::assertEquals($amount, $payment->getAmount());
    }

    /**
     * @return array
     */
    private function getItemWithShoppingPointsFixtureData(): array
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
     * @return array
     */
    private function getCleanItemFixtureData(): array
    {
        return [
            ItemTransfer::UNIT_PRICE => 450,
            ItemTransfer::UNIT_GROSS_PRICE => 450,
            ItemTransfer::UNIT_NET_PRICE => 400,
            ItemTransfer::ORIGIN_UNIT_GROSS_PRICE => 450,
            ItemTransfer::QUANTITY => 1,
        ];
    }

    /**
     * @return array
     */
    private function getItemWithBenefitVoucherFixtureData(): array
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
     * @return int[]
     */
    private function getShoppingPointBalanceFixtureData(): array
    {
        return [
            CustomerBalanceByCurrencyTransfer::EXCHANGE_RATE => 1,
            CustomerBalanceByCurrencyTransfer::AVAILABLE_BALANCE => 20,
            CustomerBalanceByCurrencyTransfer::PAYMENT_OPTION_ID => 9,
        ];
    }

    /**
     * @return int[]
     */
    private function getBenefitVoucherBalanceFixtureData(): array
    {
        return [
            CustomerBalanceByCurrencyTransfer::EXCHANGE_RATE => 1,
            CustomerBalanceByCurrencyTransfer::AVAILABLE_BALANCE => 15,
            CustomerBalanceByCurrencyTransfer::TARGET_AVAILABLE_BALANCE => 15,
            CustomerBalanceByCurrencyTransfer::PAYMENT_OPTION_ID => 10,
        ];
    }
}
