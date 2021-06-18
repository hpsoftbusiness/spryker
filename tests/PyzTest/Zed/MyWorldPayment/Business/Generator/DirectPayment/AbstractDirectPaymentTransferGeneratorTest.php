<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Zed\MyWorldPayment\Business\Generator\DirectPayment;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Pyz\Zed\MyWorldPayment\Business\Exception\MyWorldPaymentException;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Zed
 * @group MyWorldPayment
 * @group Business
 * @group Generator
 * @group DirectPayment
 * @group AbstractDirectPaymentTransferGeneratorTest
 * Add your own group annotations below this line
 */
abstract class AbstractDirectPaymentTransferGeneratorTest extends Unit
{
    protected const PAYMENT_OPTION_ID_E_VOUCHER = 1;
    protected const PAYMENT_OPTION_ID_MARKETER_E_VOUCHER = 2;
    protected const PAYMENT_OPTION_ID_CASHBACK = 6;
    protected const PAYMENT_OPTION_ID_SHOPPING_POINTS = 9;
    protected const PAYMENT_OPTION_ID_BENEFIT_VOUCHER = 10;

    protected const CURRENCY = 'Currency';
    protected const UNIT = 'Unit';

    protected const CURRENCY_CODE_EUR = 'EUR';

    /**
     * @var \PyzTest\Zed\MyWorldPayment\MyWorldPaymentBusinessTester
     */
    protected $tester;

    /**
     * @var \Pyz\Zed\MyWorldPayment\Business\Generator\DirectPayment\DirectPaymentTransferGeneratorInterface
     */
    protected $sut;

    /**
     * @dataProvider provideMwsDirectPaymentData
     *
     * @param array $quoteData
     * @param array $expectedMwsDirectPaymentData
     *
     * @return void
     */
    public function testGenerateMwsDirectPaymentOptionTransfer(array $quoteData, array $expectedMwsDirectPaymentData): void
    {
        $quoteTransfer = $this->buildQuoteTransfer($quoteData);

        $mwsDirectPaymentTransfer = $this->sut->generateMwsDirectPaymentOptionTransfer($quoteTransfer);
        $mwsDirectPaymentData = $mwsDirectPaymentTransfer->toArrayRecursiveCamelCased();
        foreach ($expectedMwsDirectPaymentData as $key => $expectedValue) {
            self::assertArrayHasKey($key, $mwsDirectPaymentData);
            self::assertEquals($expectedValue, $mwsDirectPaymentData[$key]);
        }
    }

    /**
     * @dataProvider provideIsPaymentUsedData
     *
     * @param array $quoteData
     * @param bool $expectedValue
     *
     * @return void
     */
    public function testIsPaymentUsed(array $quoteData, bool $expectedValue): void
    {
        $quoteTransfer = $this->buildQuoteTransfer($quoteData);

        $value = $this->sut->isPaymentUsed($quoteTransfer);

        self::assertEquals($expectedValue, $value);
    }

    /**
     * @return void
     */
    public function testThrowsExceptionIfPaymentMethodMissing(): void
    {
        self::expectException(MyWorldPaymentException::class);

        $quoteTransfer = $this->buildQuoteTransfer([
            QuoteTransfer::PAYMENTS => [
                PaymentTransfer::PAYMENT_METHOD => 'dummyPrepayment',
            ],
        ]);

        $this->sut->generateMwsDirectPaymentOptionTransfer($quoteTransfer);
    }

    /**
     * @param array $override
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function buildQuoteTransfer(array $override): QuoteTransfer
    {
        return (new QuoteBuilder($override))->build();
    }

    /**
     * @return array
     */
    abstract public function provideIsPaymentUsedData(): array;

    /**
     * @return array
     */
    abstract public function provideMwsDirectPaymentData(): array;
}
