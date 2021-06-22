<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Zed\MyWorldPayment\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CalculableObjectTransfer;
use Generated\Shared\Transfer\CustomerBalanceByCurrencyTransfer;
use Generated\Shared\Transfer\PaymentCodeGenerateRequestTransfer;
use Generated\Shared\Transfer\PaymentCodeValidateRequestTransfer;
use Generated\Shared\Transfer\PaymentConfirmationRequestTransfer;
use Generated\Shared\Transfer\PaymentDataRequestTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Pyz\Service\Customer\CustomerService;
use Pyz\Service\Customer\CustomerServiceInterface;
use Pyz\Zed\MyWorldPayment\Business\Exception\MyWorldPaymentException;
use Pyz\Zed\MyWorldPayment\MyWorldPaymentConfig;
use Pyz\Zed\MyWorldPayment\MyWorldPaymentDependencyProvider;
use Pyz\Zed\MyWorldPaymentApi\Business\MyWorldPaymentApiFacade;
use Pyz\Zed\MyWorldPaymentApi\Business\MyWorldPaymentApiFacadeInterface;
use Spryker\Shared\Money\Converter\DecimalToIntegerConverter;
use Spryker\Zed\SequenceNumber\Business\SequenceNumberFacade;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Zed
 * @group MyWorldPayment
 * @group Business
 * @group Facade
 * @group MyWorldPaymentFacadeTest
 * Add your own group annotations below this line
 */
class MyWorldPaymentFacadeTest extends Unit
{
    /**
     * @var \PyzTest\Zed\MyWorldPayment\MyWorldPaymentBusinessTester
     */
    protected $tester;

    /**
     * @var \Pyz\Zed\MyWorldPaymentApi\Business\MyWorldPaymentApiFacadeInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private $myWorldPaymentApiFacadeMock;

    /**
     * @return void
     */
    public function _before()
    {
        $this->setDefaultDependencies();
    }

    /**
     * @return void
     */
    public function testCreatePaymentSessionWithShoppingPointsSuccessful(): void
    {
        // Arrange
        $quoteBuilder = $this->tester->dataHelper->createQuoteBuilder(true, [
            QuoteTransfer::TOTAL_USED_SHOPPING_POINTS_AMOUNT => 2,
        ]);
        $quoteBuilder->withAnotherItem(
            $this->tester->dataHelper->createItemBuilderWithShoppingPointsDeal()
        );

        //Act
        $response = $this->tester->getFacade()->createPaymentSession($quoteBuilder->build());

        // Assert
        $this->assertTrue($response->getIsSuccess());
        $this->assertNotNull($response->getPaymentSessionResponse());
    }

    /**
     * @return void
     */
    public function testCreatePaymentSessionWithBenefitVoucherSuccess(): void
    {
        // Arrange
        $quoteBuilder = $this->tester->dataHelper->createQuoteBuilder(true, [
            QuoteTransfer::TOTAL_USED_BENEFIT_VOUCHERS_AMOUNT => 2,
            QuoteTransfer::PAYMENTS => [
                [
                    PaymentTransfer::PAYMENT_METHOD => MyWorldPaymentConfig::PAYMENT_METHOD_BENEFIT_VOUCHER_NAME,
                    PaymentTransfer::AMOUNT => 2,
                ],
            ],
        ]);
        $quoteBuilder->withItem(
            $this->tester->dataHelper->createItemBuilderWithBenefitVoucherDeal()
        );

        //Act
        $response = $this->tester->getFacade()->createPaymentSession($quoteBuilder->build());

        // Assert
        $this->assertTrue($response->getIsSuccess());
        $this->assertNotNull($response->getPaymentSessionResponse());
    }

    /**
     * @return void
     */
    public function testCreatePaymentSessionWithCashbackSuccess(): void
    {
        // Arrange
        $quoteBuilder = $this->tester->dataHelper->createQuoteBuilder(true, [
            QuoteTransfer::USE_CASHBACK_BALANCE => true,
            QuoteTransfer::TOTAL_USED_CASHBACK_BALANCE_AMOUNT => 12,
            QuoteTransfer::PAYMENTS => [
                [
                    PaymentTransfer::PAYMENT_METHOD => MyWorldPaymentConfig::PAYMENT_METHOD_CASHBACK_NAME,
                    PaymentTransfer::AMOUNT => 12,
                ],
            ],
        ]);

        //Act
        $response = $this->tester->getFacade()->createPaymentSession($quoteBuilder->build());

        // Assert
        $this->assertTrue($response->getIsSuccess());
        $this->assertNotNull($response->getPaymentSessionResponse());
    }

    /**
     * @return void
     */
    public function testCreatePaymentSessionWithEVoucherSuccess(): void
    {
        // Arrange
        $quoteBuilder = $this->tester->dataHelper->createQuoteBuilder(true, [
            QuoteTransfer::USE_E_VOUCHER_BALANCE => true,
            QuoteTransfer::TOTAL_USED_E_VOUCHER_BALANCE_AMOUNT => 12,
            QuoteTransfer::PAYMENTS => [
                [
                    PaymentTransfer::PAYMENT_METHOD => MyWorldPaymentConfig::PAYMENT_METHOD_EVOUCHER_NAME,
                    PaymentTransfer::AMOUNT => 12,
                ],
            ],
        ]);

        //Act
        $response = $this->tester->getFacade()->createPaymentSession($quoteBuilder->build());

        // Assert
        $this->assertTrue($response->getIsSuccess());
        $this->assertNotNull($response->getPaymentSessionResponse());
    }

    /**
     * @return void
     */
    public function testCreatePaymentSessionWithEVoucherMarketerSuccess(): void
    {
        // Arrange
        $quoteBuilder = $this->tester->dataHelper->createQuoteBuilder(true, [
            QuoteTransfer::USE_E_VOUCHER_ON_BEHALF_OF_MARKETER => true,
            QuoteTransfer::TOTAL_USED_E_VOUCHER_MARKETER_BALANCE_AMOUNT => 12,
            QuoteTransfer::PAYMENTS => [
                [
                    PaymentTransfer::PAYMENT_METHOD => MyWorldPaymentConfig::PAYMENT_METHOD_EVOUCHER_ON_BEHALF_OF_MARKETER_NAME,
                    PaymentTransfer::AMOUNT => 2,
                ],
            ],
        ]);

        //Act
        $response = $this->tester->getFacade()->createPaymentSession($quoteBuilder->build());

        // Assert
        $this->assertTrue($response->getIsSuccess());
        $this->assertNotNull($response->getPaymentSessionResponse());
    }

    /**
     * @return void
     */
    public function testCreatePaymentSessionWithShoppingPointsAndBenefitVoucher(): void
    {
        // Arrange
        $quoteBuilder = $this->tester->dataHelper->createQuoteBuilder(true, [
            QuoteTransfer::TOTAL_USED_SHOPPING_POINTS_AMOUNT => 2,
            QuoteTransfer::TOTAL_USED_BENEFIT_VOUCHERS_AMOUNT => 2,
            QuoteTransfer::PAYMENTS => [
                [
                    PaymentTransfer::PAYMENT_METHOD => MyWorldPaymentConfig::PAYMENT_METHOD_BENEFIT_VOUCHER_NAME,
                    PaymentTransfer::AMOUNT => 2,
                ],
            ],
        ]);

        $quoteBuilder->withAnotherItem(
            $this->tester->dataHelper->createItemBuilderWithShoppingPointsDeal()
        );
        $quoteBuilder->withAnotherItem(
            $this->tester->dataHelper->createItemBuilderWithBenefitVoucherDeal()
        );

        //Act
        $response = $this->tester->getFacade()->createPaymentSession($quoteBuilder->build());

        // Assert
        $this->assertTrue($response->getIsSuccess());
        $this->assertNotNull($response->getPaymentSessionResponse());
    }

    /**
     * @return void
     */
    public function testCreatePaymentSessionWithBenefitVoucherThrowsExceptionIfPaymentMethodMissing(): void
    {
        $this->expectException(MyWorldPaymentException::class);

        // Arrange
        $quoteBuilder = $this->tester->dataHelper->createQuoteBuilder(true, [
            QuoteTransfer::TOTAL_USED_BENEFIT_VOUCHERS_AMOUNT => 2,
        ]);

        $quoteBuilder->withAnotherItem(
            $this->tester->dataHelper->createItemBuilderWithShoppingPointsDeal()
        );
        $quoteBuilder->withAnotherItem(
            $this->tester->dataHelper->createItemBuilderWithBenefitVoucherDeal()
        );

        //Act
        $this->tester->getFacade()->createPaymentSession($quoteBuilder->build());
    }

    /**
     * @return void
     */
    public function testGenerateSmsCodeSuccess(): void
    {
        // Arrange
        $requestBuilder = $this->tester->dataHelper->createApiRequestBuilder();
        $requestBuilder->withAnotherPaymentCodeGenerateRequest(
            $this->tester->dataHelper->createSmsCodeGenerationRequestBuilder([
                PaymentCodeGenerateRequestTransfer::SESSION_ID => 'some_session_id',
            ])
        );

        // Ack
        $response = $this->tester->getFacade()->sendSmsCodeToCustomer($requestBuilder->build());

        // Assert
        $this->assertTrue($response->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testPaymentCodeValidationSuccess(): void
    {
        // Arrange
        $requestBuilder = $this->tester->dataHelper->createApiRequestBuilder();
        $requestBuilder->withAnotherPaymentCodeValidateRequest(
            $this->tester->dataHelper->createPaymentCodeValidationRequestBuilder([
                PaymentCodeValidateRequestTransfer::SESSION_ID => 'some_session_id',
                PaymentCodeValidateRequestTransfer::CONFIRMATION_CODE => 'confirm_code',
            ])
        );

        // Ack
        $response = $this->tester->getFacade()->validateSmsCode($requestBuilder->build());

        // Assert
        $this->assertTrue($response->getIsSuccess());
        $this->assertNotNull($response->getPaymentCodeValidateResponse());
    }

    /**
     * @return void
     */
    public function testConfirmPaymentSuccess(): void
    {
        // Arrange
        $requestBuilder = $this->tester->dataHelper->createApiRequestBuilder();
        $requestBuilder->withAnotherPaymentConfirmationRequest(
            $this->tester->dataHelper->createConfirmPaymentRequestBuilder([
                PaymentConfirmationRequestTransfer::SESSION_ID => 'some_session_id',
                PaymentConfirmationRequestTransfer::CONFIRMATION_CODE => 'confirm_code',
            ])
        );

        // Ack
        $response = $this->tester->getFacade()->confirmPayment($requestBuilder->build());

        // Assert
        $this->assertTrue($response->getIsSuccess());
        $this->assertNotNull($response->getPaymentConfirmationResponseTransfer());
    }

    /**
     * @return void
     */
    public function testGetPaymentSuccess(): void
    {
        // Arrange
        $requestBuilder = $this->tester->dataHelper->createApiRequestBuilder();
        $requestBuilder->withAnotherPaymentDataRequest(
            $this->tester->dataHelper->createGetPaymentRequestBuilder([
                PaymentDataRequestTransfer::PAYMENT_ID => 'some_payment_id',
            ])
        );

        // Ack
        $response = $this->tester->getFacade()->getPayment($requestBuilder->build());

        // Assert
        $this->assertTrue($response->getIsSuccess());
        $this->assertNotNull($response->getPaymentDataResponse());
    }

    /**
     * @return void
     */
    public function testSavePaymentDataInDatabaseSuccess(): void
    {
        // Arrange
        $order = $this->tester->haveSalesOrderEntity();
        $paymentDataTransfer = $this->tester->dataHelper->createPaymentDataResponseBuilder()->build();

        // Act
        $this->tester->getFacade()->saveMyWorldPaymentData($paymentDataTransfer, $order->getIdSalesOrder());
        $savedPaymentData = $this->tester->createPaymentDataQuery()->findOneByPaymentId($paymentDataTransfer->getPaymentId());

        // Assert
        $this->assertNotNull($savedPaymentData);
    }

    /**
     * @return void
     */
    public function testRecalculateEVoucherPaymentForQuoteSuccess(): void
    {
        // Arrange
        $calculableObjectTransfer = $this->tester->dataHelper->createCalculableObjectBuilder(true, [
            CalculableObjectTransfer::USE_E_VOUCHER_BALANCE => true,
        ])->build();
        $voucherDefaultBalance = $this->tester->dataHelper::CUSTOMER_BALANCE_BY_CURRENCY_DEFAULT_SEED[CustomerBalanceByCurrencyTransfer::TARGET_AVAILABLE_BALANCE] * 100;

        // Act
        $this->tester->getFacade()->recalculateEVoucherPaymentForQuote($calculableObjectTransfer);

        // Assert
        $this->assertEquals($voucherDefaultBalance, $calculableObjectTransfer->getTotalUsedEVoucherBalanceAmount());
        $this->assertCount(1, $calculableObjectTransfer->getPayments());
        $this->assertEquals(
            $this->tester->getConfig()->getEVoucherPaymentName(),
            $calculableObjectTransfer->getPayments()[0]->getPaymentMethod()
        );
    }

    /**
     * @return void
     */
    public function testRecalculateEVoucherMarketPaymentForQuoteSuccess(): void
    {
        // Arrange
        $calculableObjectTransfer = $this->tester->dataHelper->createCalculableObjectBuilder(true, [
            CalculableObjectTransfer::USE_E_VOUCHER_ON_BEHALF_OF_MARKETER => true,
        ])->build();
        $voucherDefaultBalance = $this->tester->dataHelper::CUSTOMER_BALANCE_BY_CURRENCY_DEFAULT_SEED[CustomerBalanceByCurrencyTransfer::TARGET_AVAILABLE_BALANCE] * 100;

        // Act
        $this->tester->getFacade()->recalculateEVoucherMarketerPaymentForQuote($calculableObjectTransfer);

        // Assert
        $this->assertEquals($voucherDefaultBalance, $calculableObjectTransfer->getTotalUsedEVoucherMarketerBalanceAmount());
        $this->assertCount(1, $calculableObjectTransfer->getPayments());
        $this->assertEquals(
            $this->tester->getConfig()->getEVoucherOnBehalfOfMarketerPaymentName(),
            $calculableObjectTransfer->getPayments()[0]->getPaymentMethod()
        );
    }

    /**
     * @return void
     */
    public function testRecalculateCashbackPaymentForQuoteSuccess(): void
    {
        // Arrange
        $calculableObjectTransfer = $this->tester->dataHelper->createCalculableObjectBuilder(true, [
            CalculableObjectTransfer::USE_CASHBACK_BALANCE => true,
        ])->build();
        $voucherDefaultBalance = $this->tester->dataHelper::CUSTOMER_BALANCE_BY_CURRENCY_DEFAULT_SEED[CustomerBalanceByCurrencyTransfer::TARGET_AVAILABLE_BALANCE] * 100;

        // Act
        $this->tester->getFacade()->recalculateCashbackPaymentForQuote($calculableObjectTransfer);

        // Assert
        $this->assertEquals($voucherDefaultBalance, $calculableObjectTransfer->getTotalUsedCashbackBalanceAmount());
        $this->assertCount(1, $calculableObjectTransfer->getPayments());
        $this->assertEquals(
            $this->tester->getConfig()->getCashbackPaymentName(),
            $calculableObjectTransfer->getPayments()[0]->getPaymentMethod()
        );
    }

    /**
     * @return void
     */
    public function testRecalculateShoppingPointsPaymentForQuoteSuccess(): void
    {
        // Arrange
        $itemTransfer = $this->tester->dataHelper->createItemBuilderWithShoppingPointsDeal()->build();
        $shoppingPointDealData = $itemTransfer->getShoppingPointsDeal();
        $calculableObjectTransfer = $this->tester->dataHelper->createCalculableObjectBuilder()->build();
        $calculableObjectTransfer->addItem($itemTransfer);

        // Act
        $this->tester->getFacade()->recalculateQuoteShoppingPoints($calculableObjectTransfer);

        // Assert
        $itemTransfer = $calculableObjectTransfer->getItems()[0];
        $sumGrossPrice = $shoppingPointDealData->getPrice() * $itemTransfer->getQuantity();

        $this->assertEquals($shoppingPointDealData->getShoppingPointsQuantity(), $calculableObjectTransfer->getTotalUsedShoppingPointsAmount());
        $this->assertEquals($shoppingPointDealData->getShoppingPointsQuantity(), $itemTransfer->getTotalUsedShoppingPointsAmount());
        $this->assertEquals($shoppingPointDealData->getPrice(), $itemTransfer->getUnitGrossPrice());
        $this->assertEquals($sumGrossPrice, $itemTransfer->getSumGrossPrice());
    }

    /**
     * @return void
     */
    public function testRecalculateBenefitVoucherForQuoteSuccess(): void
    {
        // Arrange
        $itemTransfer = $this->tester->dataHelper->createItemBuilderWithBenefitVoucherDeal()->build();
        $benefitVoucherDealData = $itemTransfer->getBenefitVoucherDealData();
        $calculableObjectTransfer = $this->tester->dataHelper->createCalculableObjectBuilder()->build();
        $calculableObjectTransfer->addItem($itemTransfer);

        // Act
        $this->tester->getFacade()->recalculateItemsPricesForBenefitVoucherQuote($calculableObjectTransfer);

        // Assert
        $itemTransfer = $calculableObjectTransfer->getItems()[0];

        $this->assertEquals($benefitVoucherDealData->getAmount(), $calculableObjectTransfer->getTotalUsedBenefitVouchersAmount());
        $this->assertEquals($benefitVoucherDealData->getAmount(), $itemTransfer->getTotalUsedBenefitVouchersAmount());
        $this->assertEquals($itemTransfer->getOriginUnitGrossPrice(), $itemTransfer->getUnitGrossPrice());
        $this->assertCount(1, $calculableObjectTransfer->getPayments());
        $paymentTransfer = $calculableObjectTransfer->getPayments()[0];
        $this->assertEquals(MyWorldPaymentConfig::PAYMENT_METHOD_BENEFIT_VOUCHER_NAME, $paymentTransfer->getPaymentMethod());
        $this->assertEquals($benefitVoucherDealData->getAmount(), $paymentTransfer->getAmount());
    }

    /**
     * @return void
     */
    private function setDefaultDependencies(): void
    {
        $this->myWorldPaymentApiFacadeMock = $this->mockMyWorldPaymentApiFacade();

        $this->tester->setDependency(MyWorldPaymentDependencyProvider::SERVICE_CUSTOMER, $this->createCustomerServiceMock());
        $this->tester->setDependency(MyWorldPaymentDependencyProvider::FACADE_MY_WORLD_PAYMENT_API, $this->myWorldPaymentApiFacadeMock);
        $this->tester->setDependency(MyWorldPaymentDependencyProvider::FACADE_SEQUENCE, new SequenceNumberFacade());
        $this->tester->setDependency(MyWorldPaymentDependencyProvider::DECIMAL_TO_INTEGER_CONVERTER, new DecimalToIntegerConverter());
    }

    /**
     * @return \Pyz\Service\Customer\CustomerServiceInterface
     */
    private function createCustomerServiceMock(): CustomerServiceInterface
    {
        $mock = $this->createMock(CustomerService::class);
        $mock->method('getCustomerCashbackBalanceAmount')
            ->willReturn(BusinessDataHelper::CUSTOMER_SERVICE_CASHBACK_DEFAULT_BALANCE);
        $mock->method('getUniqueAddressKey')
            ->willReturn('');
        $mock->method('getCustomerShoppingPointsBalanceAmount')
            ->willReturn(BusinessDataHelper::CUSTOMER_SERVICE_SHOPPING_POINTS_DEFAULT_BALANCE);
        $mock->method('getCustomerEVoucherBalanceAmount')
            ->willReturn(BusinessDataHelper::CUSTOMER_SERVICE_E_VOUCHER_DEFAULT_BALANCE);
        $mock->method('getCustomerMarketerEVoucherBalanceAmount')
            ->willReturn(BusinessDataHelper::CUSTOMER_SERVICE_MARKETER_DEFAULT_BALANCE);
        $mock->method('getCustomerBenefitVoucherBalanceAmount')
            ->willReturn(BusinessDataHelper::CUSTOMER_SERVICE_BENEFIT_EVOUCHER_DEFAULT_BALANCE);

        return $mock;
    }

    /**
     * @return \Pyz\Zed\MyWorldPaymentApi\Business\MyWorldPaymentApiFacadeInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private function mockMyWorldPaymentApiFacade(): MyWorldPaymentApiFacadeInterface
    {
        $mock = $this->createMock(MyWorldPaymentApiFacade::class);
        $mock->method('performCreatePaymentSessionApiCall')
            ->willReturn($this->tester->dataHelper->createResponseBuilder()->build());
        $mock->method('performGenerateSmsCodeApiCall')
            ->willReturn($this->tester->dataHelper->createResponseBuilder()->build());
        $mock->method('performValidateSmsCodeApiCall')
            ->willReturn($this->tester->dataHelper->createResponseBuilder()->build());
        $mock->method('performConfirmPaymentApiCall')
            ->willReturn($this->tester->dataHelper->createResponseBuilder()->build());
        $mock->method('performGetPaymentApiCall')
            ->willReturn($this->tester->dataHelper->createResponseBuilder()->build());
        $mock->method('performCreateRefundApiCall')
            ->willReturn($this->tester->dataHelper->createResponseBuilder()->build());

        return $mock;
    }
}
