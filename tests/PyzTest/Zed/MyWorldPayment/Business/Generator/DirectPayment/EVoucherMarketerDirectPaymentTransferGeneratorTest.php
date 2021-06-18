<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Zed\MyWorldPayment\Business\Generator\DirectPayment;

use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\MwsDirectPaymentOptionTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Pyz\Zed\MyWorldPayment\Business\Generator\DirectPayment\EVoucherMarketerDirectPaymentTransferGenerator;
use Pyz\Zed\MyWorldPayment\MyWorldPaymentConfig;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Zed
 * @group MyWorldPayment
 * @group Business
 * @group Generator
 * @group DirectPayment
 * @group EVoucherMarketerDirectPaymentTransferGeneratorTest
 * Add your own group annotations below this line
 */
class EVoucherMarketerDirectPaymentTransferGeneratorTest extends AbstractDirectPaymentTransferGeneratorTest
{
    /**
     * @return void
     */
    protected function _before(): void
    {
        $this->sut = new EVoucherMarketerDirectPaymentTransferGenerator($this->tester->getConfig());
    }

    /**
     * @return array
     */
    public function provideIsPaymentUsedData(): array
    {
        return [
            'Only Marketer EVoucher total used amount set' => [
                [
                    QuoteTransfer::TOTAL_USED_E_VOUCHER_MARKETER_BALANCE_AMOUNT => 100,
                ],
                false,
            ],
            'Marketer EVoucher total used amount set with use marketer eVoucher flag' => [
                [
                    QuoteTransfer::TOTAL_USED_E_VOUCHER_MARKETER_BALANCE_AMOUNT => 100,
                    QuoteTransfer::USE_E_VOUCHER_ON_BEHALF_OF_MARKETER => true,
                ],
                true,
            ],
            'Marketer EVoucher total used amount not set' => [
                [
                    QuoteTransfer::TOTAL_USED_E_VOUCHER_MARKETER_BALANCE_AMOUNT => null,
                ],
                false,
            ],
        ];
    }

    /**
     * @return array[][]
     */
    public function provideMwsDirectPaymentData(): array
    {
        return [
            'Marketer EVoucher payment present' => [
                'quote data' => [
                    QuoteTransfer::PAYMENTS => [
                        [
                            PaymentTransfer::PAYMENT_METHOD => MyWorldPaymentConfig::PAYMENT_METHOD_EVOUCHER_ON_BEHALF_OF_MARKETER_NAME,
                            PaymentTransfer::AMOUNT => 100,
                        ],
                    ],
                    QuoteTransfer::TOTAL_USED_E_VOUCHER_MARKETER_BALANCE_AMOUNT => 150,
                    QuoteTransfer::CURRENCY => [
                        CurrencyTransfer::CODE => self::CURRENCY_CODE_EUR,
                    ],
                ],
                'expected direct payment data' => [
                    MwsDirectPaymentOptionTransfer::AMOUNT => 100,
                    MwsDirectPaymentOptionTransfer::PAYMENT_OPTION_ID => self::PAYMENT_OPTION_ID_MARKETER_E_VOUCHER,
                    MwsDirectPaymentOptionTransfer::UNIT => self::CURRENCY_CODE_EUR,
                    MwsDirectPaymentOptionTransfer::UNIT_TYPE => self::CURRENCY,
                ],
            ],
        ];
    }
}
