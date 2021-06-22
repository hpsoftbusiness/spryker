<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Zed\MyWorldPayment\Communication\Plugin\Refund;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\BenefitVoucherDealDataTransfer;
use Generated\Shared\Transfer\CalculableObjectTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Pyz\Zed\MyWorldPayment\Communication\Plugin\Calculation\BenefitVoucherOrderCalculationPlugin;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Zed
 * @group MyWorldPayment
 * @group Communication
 * @group Plugin
 * @group Refund
 * @group BenefitVoucherOrderCalculationPluginTest
 * Add your own group annotations below this line
 */
class BenefitVoucherOrderCalculationPluginTest extends Unit
{
    /**
     * @var \PyzTest\Zed\MyWorldPayment\MyWorldPaymentCommunicationTester
     */
    protected $tester;

    /**
     * @var \Pyz\Zed\MyWorldPayment\Communication\Plugin\Calculation\BenefitVoucherOrderCalculationPlugin
     */
    private $sut;

    /**
     * @return void
     */
    protected function _before(): void
    {
        $this->sut = $this->createBenefitVoucherOrderCalculationPlugin();
    }

    /**
     * @return void
     */
    public function testItemsBenefitVoucherAmountSplitByItemQuantity(): void
    {
        $calculableObjectTransfer = $this->tester->buildCalculableObjectTransfer([
            CalculableObjectTransfer::ITEMS => [
                [
                    ItemTransfer::USE_BENEFIT_VOUCHER => true,
                    ItemTransfer::QUANTITY => 1,
                    ItemTransfer::TOTAL_USED_BENEFIT_VOUCHERS_AMOUNT => 100,
                    ItemTransfer::BENEFIT_VOUCHER_DEAL_DATA => [
                        BenefitVoucherDealDataTransfer::IS_STORE => true,
                        BenefitVoucherDealDataTransfer::AMOUNT => 50,
                        BenefitVoucherDealDataTransfer::SALES_PRICE => 800,
                    ],
                ],
                [
                    ItemTransfer::USE_BENEFIT_VOUCHER => true,
                    ItemTransfer::QUANTITY => 1,
                    ItemTransfer::TOTAL_USED_BENEFIT_VOUCHERS_AMOUNT => 100,
                    ItemTransfer::BENEFIT_VOUCHER_DEAL_DATA => [
                        BenefitVoucherDealDataTransfer::IS_STORE => true,
                        BenefitVoucherDealDataTransfer::AMOUNT => 50,
                        BenefitVoucherDealDataTransfer::SALES_PRICE => 800,
                    ],
                ],
                [
                    ItemTransfer::QUANTITY => 1,
                ],
                [
                    ItemTransfer::QUANTITY => 1,
                    ItemTransfer::USE_BENEFIT_VOUCHER => true,
                ],
                [
                    ItemTransfer::QUANTITY => 1,
                    ItemTransfer::USE_BENEFIT_VOUCHER => true,
                    ItemTransfer::BENEFIT_VOUCHER_DEAL_DATA => [
                        BenefitVoucherDealDataTransfer::IS_STORE => false,
                    ],
                ],
                [
                    ItemTransfer::USE_BENEFIT_VOUCHER => true,
                    ItemTransfer::QUANTITY => 3,
                    ItemTransfer::IS_QUANTITY_SPLITTABLE => false,
                    ItemTransfer::TOTAL_USED_BENEFIT_VOUCHERS_AMOUNT => 150,
                    ItemTransfer::BENEFIT_VOUCHER_DEAL_DATA => [
                        BenefitVoucherDealDataTransfer::IS_STORE => true,
                        BenefitVoucherDealDataTransfer::AMOUNT => 50,
                        BenefitVoucherDealDataTransfer::SALES_PRICE => 800,
                    ],
                ],
            ],
        ]);

        $this->sut->recalculate($calculableObjectTransfer);

        self::assertEquals(50, $calculableObjectTransfer->getItems()[0]->getTotalUsedBenefitVouchersAmount());
        self::assertEquals(50, $calculableObjectTransfer->getItems()[1]->getTotalUsedBenefitVouchersAmount());
        self::assertNull($calculableObjectTransfer->getItems()[2]->getTotalUsedBenefitVouchersAmount());
        self::assertNull($calculableObjectTransfer->getItems()[3]->getTotalUsedBenefitVouchersAmount());
        self::assertNull($calculableObjectTransfer->getItems()[4]->getTotalUsedBenefitVouchersAmount());
        self::assertEquals(150, $calculableObjectTransfer->getItems()[5]->getTotalUsedBenefitVouchersAmount());
    }

    /**
     * @return \Pyz\Zed\MyWorldPayment\Communication\Plugin\Calculation\BenefitVoucherOrderCalculationPlugin
     */
    private function createBenefitVoucherOrderCalculationPlugin(): BenefitVoucherOrderCalculationPlugin
    {
        return new BenefitVoucherOrderCalculationPlugin();
    }
}
