<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Zed\Adyen\Business\Hook\Mapper\MakePayment;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\AdyenPaymentTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Pyz\Shared\Adyen\AdyenConfig as SharedAdyenConfig;
use Pyz\Shared\Adyen\AdyenConstants;
use Pyz\Shared\DummyPrepayment\DummyPrepaymentConfig;
use Pyz\Shared\MyWorldPayment\MyWorldPaymentConfig as SharedMyWorldPaymentConfig;
use Pyz\Zed\Adyen\AdyenConfig;
use Pyz\Zed\Adyen\Business\Hook\Mapper\MakePayment\CreditCardMapper;
use Pyz\Zed\MyWorldPayment\MyWorldPaymentConfig;
use SprykerEco\Zed\Adyen\Business\Exception\AdyenMethodMapperException;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Zed
 * @group Adyen
 * @group Business
 * @group Hook
 * @group Mapper
 * @group MakePayment
 * @group CreditCardMapperTest
 * Add your own group annotations below this line
 */
class CreditCardMapperTest extends Unit
{
    private const SPLIT_COMMISSION_AMOUNT = 0.015;

    /**
     * @var \PyzTest\Zed\Adyen\AdyenBusinessTester
     */
    protected $tester;

    /**
     * @var \Pyz\Zed\Adyen\Business\Hook\Mapper\MakePayment\CreditCardMapper
     */
    private $sut;

    /**
     * @return void
     */
    protected function _before(): void
    {
        $this->sut = $this->createCreditCardMapper();
        $this->tester->mockEnvironmentConfig(AdyenConstants::SPLIT_ACCOUNT_COMMISSION_INTEREST, self::SPLIT_COMMISSION_AMOUNT);
    }

    /**
     * @return void
     */
    public function testPaymentRequestAmountSetFromAdyenPaymentData(): void
    {
        $quoteTransfer = $this->tester->buildQuoteTransferWithPayments(
            [
                PaymentTransfer::PAYMENT_METHOD => SharedAdyenConfig::ADYEN_CREDIT_CARD,
                PaymentTransfer::PAYMENT_PROVIDER => SharedAdyenConfig::PROVIDER_NAME,
                PaymentTransfer::AMOUNT => 2000,
                PaymentTransfer::ADYEN_PAYMENT => [
                    AdyenPaymentTransfer::SPLIT_COMMISSION_REFERENCE => $this->tester::COMMISSION_REFERENCE,
                    AdyenPaymentTransfer::SPLIT_MARKETPLACE_REFERENCE => $this->tester::MARKETPLACE_REFERENCE,
                ],
            ],
            [
                [
                    PaymentTransfer::PAYMENT_METHOD => MyWorldPaymentConfig::PAYMENT_METHOD_BENEFIT_VOUCHER_NAME,
                    PaymentTransfer::PAYMENT_PROVIDER => SharedMyWorldPaymentConfig::PAYMENT_PROVIDER_NAME_MY_WORLD,
                    PaymentTransfer::AMOUNT => 1500,
                ],
            ]
        );

        $adyenApiRequestTransfer = $this->sut->buildPaymentRequestTransfer($quoteTransfer);

        $makePaymentRequest = $adyenApiRequestTransfer->getMakePaymentRequest();
        self::assertNotNull($makePaymentRequest);

        self::assertEquals(2000, $makePaymentRequest->getAmount()->getValue());
        self::assertEquals($this->tester::CURRENCY_CODE_EUR, $makePaymentRequest->getAmount()->getCurrency());

        self::assertCount(2, $makePaymentRequest->getSplits());

        $marketplaceSplitTransfer = $this->tester->findSplitTransferByType(
            $makePaymentRequest->getSplits(),
            SharedAdyenConfig::SPLIT_TYPE_MARKETPLACE
        );
        self::assertNotNull($marketplaceSplitTransfer);
        self::assertEquals($this->tester::MARKETPLACE_REFERENCE, $marketplaceSplitTransfer->getReference());
        self::assertEquals($marketplaceSplitTransfer->getAmount()->getValue(), 1970);

        $commissionSplitTransfer = $this->tester->findSplitTransferByType(
            $makePaymentRequest->getSplits(),
            SharedAdyenConfig::SPLIT_TYPE_COMMISSION
        );
        self::assertNotNull($commissionSplitTransfer);
        self::assertEquals($this->tester::COMMISSION_REFERENCE, $commissionSplitTransfer->getReference());
        self::assertEquals($commissionSplitTransfer->getAmount()->getValue(), 30);
    }

    /**
     * @return void
     */
    public function testPaymentRequestBuildThrowsExceptionIfAdyenPaymentNotFound(): void
    {
        self::expectException(AdyenMethodMapperException::class);

        $quoteTransfer = $this->tester->buildQuoteTransferWithPayments(
            [
                PaymentTransfer::PAYMENT_METHOD => DummyPrepaymentConfig::DUMMY_PREPAYMENT,
                PaymentTransfer::PAYMENT_PROVIDER => DummyPrepaymentConfig::DUMMY_PREPAYMENT,
            ],
            [
                [
                    PaymentTransfer::PAYMENT_METHOD => MyWorldPaymentConfig::PAYMENT_METHOD_BENEFIT_VOUCHER_NAME,
                    PaymentTransfer::PAYMENT_PROVIDER => SharedMyWorldPaymentConfig::PAYMENT_PROVIDER_NAME_MY_WORLD,
                    PaymentTransfer::AMOUNT => 1500,
                ],
            ]
        );

        $this->sut->buildPaymentRequestTransfer($quoteTransfer);
    }

    /**
     * @return \Pyz\Zed\Adyen\Business\Hook\Mapper\MakePayment\CreditCardMapper
     */
    private function createCreditCardMapper(): CreditCardMapper
    {
        return new CreditCardMapper(new AdyenConfig());
    }
}
