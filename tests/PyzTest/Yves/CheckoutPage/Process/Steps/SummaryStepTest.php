<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Yves\CheckoutPage\Process\Steps;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\PaymentBuilder;
use Generated\Shared\Transfer\MyWorldApiResponseTransfer;
use Generated\Shared\Transfer\PaymentCodeValidateResponseTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Pyz\Client\MyWorldPayment\MyWorldPaymentClientInterface;
use Pyz\Shared\MyWorldPayment\MyWorldPaymentConfig as SharedMyWorldPaymentConfig;
use Pyz\Yves\CheckoutPage\CheckoutPageConfig;
use Pyz\Yves\CheckoutPage\Process\Steps\PreConditionCheckerInterface;
use Pyz\Yves\CheckoutPage\Process\Steps\SummaryStep;
use Pyz\Yves\CheckoutPage\Process\Steps\SummaryStep\PostConditionChecker;
use Pyz\Yves\CheckoutPage\Process\Steps\SummaryStep\PreConditionChecker;
use Pyz\Zed\MyWorldPayment\MyWorldPaymentConfig;
use PyzTest\Yves\CheckoutPage\CheckoutPageProcessTester;
use Spryker\Shared\Translator\TranslatorInterface;
use Spryker\Yves\Messenger\FlashMessenger\FlashMessengerInterface;
use SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToCheckoutClientInterface;
use SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToProductBundleClientInterface;
use SprykerShop\Yves\CheckoutPage\Dependency\Service\CheckoutPageToShipmentServiceInterface;
use SprykerShop\Yves\CheckoutPage\Process\Steps\PostConditionCheckerInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Yves
 * @group CheckoutPage
 * @group Process
 * @group Steps
 * @group SummaryStepTest
 * Add your own group annotations below this line
 */
class SummaryStepTest extends Unit
{
    private const STEP_ROUTE = 'summary-step-test-route';
    private const STEP_ESCAPE_ROUTE = 'summary-step-test-escape-route';

    private const SMS_CODE = 'SMS';

    /**
     * @var \PyzTest\Yves\CheckoutPage\CheckoutPageProcessTester
     */
    protected $tester;

    /**
     * @var \Pyz\Yves\CheckoutPage\Process\Steps\SummaryStep
     */
    private $sut;

    /**
     * @var \Pyz\Client\MyWorldPayment\MyWorldPaymentClientInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private $myWorldPaymentClientMock;

    /**
     * @var \Spryker\Yves\Messenger\FlashMessenger\FlashMessengerInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private $messengerMock;

    /**
     * @return void
     */
    protected function _before(): void
    {
        $this->myWorldPaymentClientMock = $this->mockMyWorldPaymentClient();
        $this->messengerMock = $this->mockFlashMessenger();
        $this->sut = $this->createSummaryStep();
    }

    /**
     * @return void
     */
    public function testPreCheckFailsIfPaymentSessionCreationFailed(): void
    {
        $quoteTransfer = $this->tester->buildQuoteTransfer();
        $benefitVoucherPayment = $this->buildPaymentTransfer([
            PaymentTransfer::PAYMENT_PROVIDER => SharedMyWorldPaymentConfig::PAYMENT_PROVIDER_NAME_MY_WORLD,
            PaymentTransfer::PAYMENT_METHOD_NAME => MyWorldPaymentConfig::PAYMENT_METHOD_BENEFIT_VOUCHER_NAME,
        ]);
        $quoteTransfer->addPayment($benefitVoucherPayment);

        $isValid = $this->sut->preCondition($quoteTransfer);

        self::assertFalse($isValid);
    }

    /**
     * @return void
     */
    public function testPreCheckSucceedsIfNoMyWorldPaymentMethodsUsed(): void
    {
        $quoteTransfer = $this->tester->buildQuoteTransfer();
        $isValid = $this->sut->preCondition($quoteTransfer);

        self::assertTrue($isValid);
    }

    /**
     * @return void
     */
    public function testPaymentConfirmedWithCodeIfRequired(): void
    {
        $quoteTransfer = $this->tester->buildQuoteTransfer();
        $benefitVoucherPayment = $this->buildPaymentTransfer([
            PaymentTransfer::PAYMENT_PROVIDER => SharedMyWorldPaymentConfig::PAYMENT_PROVIDER_NAME_MY_WORLD,
            PaymentTransfer::PAYMENT_METHOD_NAME => MyWorldPaymentConfig::PAYMENT_METHOD_BENEFIT_VOUCHER_NAME,
        ]);
        $quoteTransfer->addPayment($benefitVoucherPayment);
        $quoteTransfer->setMyWorldPaymentSessionId(CheckoutPageProcessTester::PAYMENT_SESSION_ID);
        $quoteTransfer->setMyWorldPaymentIsSmsAuthenticationRequired(true);

        $this->myWorldPaymentClientMock
            ->expects(self::once())
            ->method('validateSmsCode')
            ->willReturn(
                (new MyWorldApiResponseTransfer())->setPaymentCodeValidateResponse(
                    (new PaymentCodeValidateResponseTransfer())->setIsValid(true)
                )
            );

        $this->messengerMock
            ->expects(self::never())
            ->method('addErrorMessage');

        $isValid = $this->sut->preCondition($quoteTransfer);

        self::assertTrue($isValid);

        $request = $this->createRequest(true);
        $quoteTransfer->setSmsCode(self::SMS_CODE);
        $this->sut->execute($request, $quoteTransfer);

        self::assertTrue($quoteTransfer->getCheckoutConfirmed());
    }

    /**
     * @return void
     */
    public function testErrorShownIfPaymentConfirmationCodeInvalid(): void
    {
        $quoteTransfer = $this->tester->buildQuoteTransfer();
        $benefitVoucherPayment = $this->buildPaymentTransfer([
            PaymentTransfer::PAYMENT_PROVIDER => SharedMyWorldPaymentConfig::PAYMENT_PROVIDER_NAME_MY_WORLD,
            PaymentTransfer::PAYMENT_METHOD_NAME => MyWorldPaymentConfig::PAYMENT_METHOD_BENEFIT_VOUCHER_NAME,
        ]);
        $quoteTransfer->addPayment($benefitVoucherPayment);
        $quoteTransfer->setMyWorldPaymentSessionId(CheckoutPageProcessTester::PAYMENT_SESSION_ID);
        $quoteTransfer->setMyWorldPaymentIsSmsAuthenticationRequired(true);

        $this->myWorldPaymentClientMock
            ->expects(self::once())
            ->method('validateSmsCode')
            ->willReturn(
                (new MyWorldApiResponseTransfer())->setPaymentCodeValidateResponse(
                    (new PaymentCodeValidateResponseTransfer())->setIsValid(false)
                )
            );

        $this->messengerMock
            ->expects(self::once())
            ->method('addErrorMessage');

        $isValid = $this->sut->preCondition($quoteTransfer);

        self::assertTrue($isValid);

        $request = $this->createRequest(true);
        $quoteTransfer->setSmsCode(self::SMS_CODE);
        $this->sut->execute($request, $quoteTransfer);

        self::assertFalse($quoteTransfer->getCheckoutConfirmed());
    }

    /**
     * @param bool $withSMS
     *
     * @return \Symfony\Component\HttpFoundation\Request
     */
    private function createRequest(bool $withSMS = false): Request
    {
        $request = new Request();
        $request->setMethod('POST');

        if ($withSMS) {
            $request->attributes->set('summaryForm', [QuoteTransfer::SMS_CODE => self::SMS_CODE]);
        }

        return $request;
    }

    /**
     * @param array $paymentOverride
     *
     * @return \Generated\Shared\Transfer\PaymentTransfer
     */
    private function buildPaymentTransfer(array $paymentOverride = []): PaymentTransfer
    {
        return (new PaymentBuilder($paymentOverride))->build();
    }

    /**
     * @return \Pyz\Yves\CheckoutPage\Process\Steps\SummaryStep
     */
    private function createSummaryStep(): SummaryStep
    {
        return new SummaryStep(
            $this->mockProductBundleClient(),
            $this->mockShipmentService(),
            $this->getConfig(),
            self::STEP_ROUTE,
            self::STEP_ESCAPE_ROUTE,
            $this->mockCheckoutClient(),
            $this->createPreConditionChecker(),
            $this->createPostConditionChecker()
        );
    }

    /**
     * @return \SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToProductBundleClientInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private function mockProductBundleClient(): CheckoutPageToProductBundleClientInterface
    {
        return $this->createMock(CheckoutPageToProductBundleClientInterface::class);
    }

    /**
     * @return \SprykerShop\Yves\CheckoutPage\Dependency\Service\CheckoutPageToShipmentServiceInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private function mockShipmentService(): CheckoutPageToShipmentServiceInterface
    {
        return $this->createMock(CheckoutPageToShipmentServiceInterface::class);
    }

    /**
     * @return \SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToCheckoutClientInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private function mockCheckoutClient(): CheckoutPageToCheckoutClientInterface
    {
        return $this->createMock(CheckoutPageToCheckoutClientInterface::class);
    }

    /**
     * @return \Pyz\Yves\CheckoutPage\Process\Steps\PreConditionCheckerInterface
     */
    private function createPreConditionChecker(): PreConditionCheckerInterface
    {
        return new PreConditionChecker();
    }

    /**
     * @return \SprykerShop\Yves\CheckoutPage\Process\Steps\PostConditionCheckerInterface
     */
    private function createPostConditionChecker(): PostConditionCheckerInterface
    {
        return new PostConditionChecker(
            $this->myWorldPaymentClientMock,
            $this->messengerMock,
            $this->mockTranslator()
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
     * @return \Pyz\Yves\CheckoutPage\CheckoutPageConfig
     */
    private function getConfig(): CheckoutPageConfig
    {
        return new CheckoutPageConfig();
    }
}
