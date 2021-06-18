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
use Pyz\Zed\MyWorldPayment\Business\Generator\DirectPayment\BenefitVoucherDirectPaymentTransferGenerator;
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
 * @group BenefitVoucherDirectPaymentTransferGeneratorTest
 * Add your own group annotations below this line
 */
class BenefitVoucherDirectPaymentTransferGeneratorTest extends AbstractDirectPaymentTransferGeneratorTest
{
    /**
     * @return void
     */
    protected function _before(): void
    {
        $this->sut = new BenefitVoucherDirectPaymentTransferGenerator($this->tester->getConfig());
    }

    /**
     * @return array
     */
    public function provideIsPaymentUsedData(): array
    {
        return [
            'Benefit Voucher total used amount set' => [
                [
                    QuoteTransfer::TOTAL_USED_BENEFIT_VOUCHERS_AMOUNT => 10,
                ],
                true,
            ],
            'Benefit Voucher total used amount not set' => [
                [
                    QuoteTransfer::TOTAL_USED_BENEFIT_VOUCHERS_AMOUNT => null,
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
            'Benefit Voucher payment present' => [
                'quote data' => [
                    QuoteTransfer::PAYMENTS => [
                        [
                            PaymentTransfer::PAYMENT_METHOD => MyWorldPaymentConfig::PAYMENT_METHOD_BENEFIT_VOUCHER_NAME,
                            PaymentTransfer::AMOUNT => 100,
                        ],
                    ],
                    QuoteTransfer::CURRENCY => [
                        CurrencyTransfer::CODE => self::CURRENCY_CODE_EUR,
                    ],
                ],
                'expected direct payment data' => [
                    MwsDirectPaymentOptionTransfer::AMOUNT => 100,
                    MwsDirectPaymentOptionTransfer::PAYMENT_OPTION_ID => self::PAYMENT_OPTION_ID_BENEFIT_VOUCHER,
                    MwsDirectPaymentOptionTransfer::UNIT => self::CURRENCY_CODE_EUR,
                    MwsDirectPaymentOptionTransfer::UNIT_TYPE => self::CURRENCY,
                ],
            ],
        ];
    }
}
