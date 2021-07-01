<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Yves\CheckoutPage\Form\DataProvider;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\BenefitVoucherDealDataTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ShoppingPointsDealTransfer;
use Pyz\Yves\CheckoutPage\Form\DataProvider\BenefitFormDataProvider;
use Pyz\Yves\CheckoutPage\Form\Steps\BenefitDeal\BenefitDealCollectionForm;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Yves
 * @group CheckoutPage
 * @group Form
 * @group DataProvider
 * @group BenefitFormDataProviderTest
 * Add your own group annotations below this line
 */
class BenefitFormDataProviderTest extends Unit
{
    private const ITEM_SKU_WITH_BV_DEAL_ACTIVE = 'BV_0001';
    private const ITEM_SKU_WITH_SP_DEAL_ACTIVE = 'SP_0001';

    /**
     * @var \PyzTest\Yves\CheckoutPage\CheckoutPageFormTester
     */
    protected $tester;

    /**
     * @var \Pyz\Yves\CheckoutPage\Form\DataProvider\BenefitFormDataProvider
     */
    private $sut;

    /**
     * @return void
     */
    protected function _before(): void
    {
        $this->sut = $this->createBenefitFormDataProvider();
    }

    /**
     * @return void
     */
    public function testFormOptionsAppendedWithAllItems(): void
    {
        $quoteTransfer = $this->tester->buildQuoteTransfer([], [
            [
                ItemTransfer::SHOPPING_POINTS_DEAL => [
                    ShoppingPointsDealTransfer::IS_ACTIVE => true,
                ],
                ItemTransfer::SKU => self::ITEM_SKU_WITH_SP_DEAL_ACTIVE,
            ],
            [
                ItemTransfer::SHOPPING_POINTS_DEAL => [
                    ShoppingPointsDealTransfer::IS_ACTIVE => false,
                ],
            ],
            [
                ItemTransfer::BENEFIT_VOUCHER_DEAL_DATA => [
                    BenefitVoucherDealDataTransfer::IS_STORE => true,
                ],
                ItemTransfer::SKU => self::ITEM_SKU_WITH_BV_DEAL_ACTIVE,
            ],
            [
                ItemTransfer::BENEFIT_VOUCHER_DEAL_DATA => [
                    BenefitVoucherDealDataTransfer::IS_STORE => false,
                ],
            ],
        ]);

        $options = $this->sut->getOptions($quoteTransfer);
        /**
         * @var \Generated\Shared\Transfer\ItemTransfer[] $benefitDealItems
         */
        $benefitDealItems = $options[BenefitDealCollectionForm::OPTION_KEY_ITEMS] ?? null;
        self::assertNotNull($benefitDealItems);
        self::assertCount($quoteTransfer->getItems()->count(), $benefitDealItems);
    }

    /**
     * @return \Pyz\Yves\CheckoutPage\Form\DataProvider\BenefitFormDataProvider
     */
    private function createBenefitFormDataProvider(): BenefitFormDataProvider
    {
        return new BenefitFormDataProvider();
    }
}
