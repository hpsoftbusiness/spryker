<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Zed\Nopayment\Business\Nopayment;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\Transfer\PaymentMethodsTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Pyz\Zed\Nopayment\Business\Nopayment\NopaymentMethodFilter;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Zed
 * @group Nopayment
 * @group Business
 * @group Nopayment
 * @group NopaymentMethodFilterTest
 * Add your own group annotations below this line
 */
class NopaymentMethodFilterTest extends Unit
{
    /**
     * @var \PyzTest\Zed\Nopayment\NopaymentBusinessTester
     */
    protected $tester;

    /**
     * @var \Pyz\Zed\Nopayment\Business\Nopayment\NopaymentMethodFilter|\PHPUnit\Framework\MockObject\MockObject
     */
    private $sut;

    /**
     * @return void
     */
    protected function _before(): void
    {
        $this->sut = $this->partialyMockNoPaymentMethodFilter();
    }

    /**
     * @dataProvider provideNopaymentMethodFilterTestData
     *
     * @param int $priceToPay
     * @param bool $useCashback
     * @param bool $useEVoucher
     * @param bool $useEVoucherOnBehalfOfMarketer
     * @param string $expectedMethodCall
     *
     * @return void
     */
    public function testPaymentMethodsFiltering(
        int $priceToPay,
        bool $useCashback,
        bool $useEVoucher,
        bool $useEVoucherOnBehalfOfMarketer,
        string $expectedMethodCall
    ): void {
        $quoteTransfer = $this->buildQuoteTransfer([
            QuoteTransfer::USE_CASHBACK_BALANCE => $useCashback,
            QuoteTransfer::USE_E_VOUCHER_BALANCE => $useEVoucher,
            QuoteTransfer::USE_E_VOUCHER_ON_BEHALF_OF_MARKETER => $useEVoucherOnBehalfOfMarketer,
            QuoteTransfer::TOTALS => [
                TotalsTransfer::PRICE_TO_PAY => $priceToPay,
            ],
        ]);

        $this->sut
            ->expects(self::once())
            ->method($expectedMethodCall);

        $this->sut->filterPaymentMethods(new PaymentMethodsTransfer(), $quoteTransfer);
    }

    /**
     * @return array[]
     */
    public function provideNopaymentMethodFilterTestData(): array
    {
        return [
            'NoPaymentMethodsDisallowedIfPriceToPayMoreThanZero' => [
                500, false, false, false, 'disallowNoPaymentMethods',
            ],
            'NoPaymentMethodsDisallowedIfPriceToPayMoreThanZeroAndCashbackMethodUsed' => [
                500, true, false, false, 'disallowNoPaymentMethods',
            ],
            'NoPaymentMethodsDisallowedIfPriceToPayMoreThanZeroAndEVoucherMethodUsed' => [
                500, false, true, false, 'disallowNoPaymentMethods',
            ],
            'NoPaymentMethodsDisallowedIfPriceToPayMoreThanZeroAndEVoucherOnBehalfOfMarketerMethodUsed' => [
                500, false, false, true, 'disallowNoPaymentMethods',
            ],
            'NoPaymentMethodsAllowedIfPriceToPayIsZeroAndCashbackMethodUsed' => [
                0, true, false, false, 'disallowRegularPaymentMethods',
            ],
            'NoPaymentMethodsAllowedIfPriceToPayIsZeroAndEVoucherMethodUsed' => [
                0, false, true, false, 'disallowRegularPaymentMethods',
            ],
            'NoPaymentMethodsAllowedIfPriceToPayIsZeroAndEVoucherOnBehalfOfMarketerMethodUsed' => [
                0, false, false, true, 'disallowRegularPaymentMethods',
            ],
        ];
    }

    /**
     * @param array $override
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    private function buildQuoteTransfer(array $override): QuoteTransfer
    {
        return (new QuoteBuilder($override))->build();
    }

    /**
     * @return \Pyz\Zed\Nopayment\Business\Nopayment\NopaymentMethodFilter|\PHPUnit\Framework\MockObject\MockObject
     */
    private function partialyMockNoPaymentMethodFilter(): NopaymentMethodFilter
    {
        return $this->getMockBuilder(NopaymentMethodFilter::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'disallowRegularPaymentMethods',
                'disallowNoPaymentMethods',
            ])
            ->getMock();
    }
}
