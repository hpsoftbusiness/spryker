<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Zed\Sales\Business\Model\OrderItem;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\BenefitVoucherDealDataTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Pyz\Zed\Sales\Business\Model\OrderItem\OrderItemTransformer;
use Pyz\Zed\Sales\Communication\Plugin\OrderItem\BenefitVoucherAmountItemTransformerPlugin;
use Spryker\Zed\Sales\Business\Model\OrderItem\OrderItemTransformerInterface;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Zed
 * @group Sales
 * @group Business
 * @group Model
 * @group OrderItem
 * @group OrderItemTransformerTest
 * Add your own group annotations below this line
 */
class OrderItemTransformerTest extends Unit
{
    /**
     * @var \PyzTest\Zed\Sales\SalesBusinessTester
     */
    protected $tester;

    /**
     * @var \Spryker\Zed\Sales\Business\Model\OrderItem\OrderItemTransformerInterface
     */
    private $sut;

    /**
     * @return void
     */
    protected function _before(): void
    {
        $this->sut = $this->createOrderItemTransformer();
    }

    /**
     * @dataProvider provideTestData
     *
     * @param array $itemData
     * @param array $expectedItemBenefitVoucherAmounts
     *
     * @return void
     */
    public function testTransformSplittableItemsSplitsBenefitVoucherAmount(
        array $itemData,
        array $expectedItemBenefitVoucherAmounts
    ): void {
        $itemTransfer = $this->tester->buildItemTransfer($itemData);

        $itemCollectionTransfer = $this->sut->transformSplittableItem($itemTransfer);

        foreach ($itemCollectionTransfer->getItems() as $key => $item) {
            self::assertEquals($expectedItemBenefitVoucherAmounts[$key], $item->getTotalUsedBenefitVouchersAmount());
        }
    }

    /**
     * @return array
     */
    public function provideTestData(): array
    {
        return [
            'total applicable amount of benefit vouchers used' => [
                'itemData' => [
                    ItemTransfer::QUANTITY => 3,
                    ItemTransfer::USE_BENEFIT_VOUCHER => true,
                    ItemTransfer::TOTAL_USED_BENEFIT_VOUCHERS_AMOUNT => 600,
                    ItemTransfer::BENEFIT_VOUCHER_DEAL_DATA => [
                        BenefitVoucherDealDataTransfer::AMOUNT => 200,
                    ],
                ],
                'expectedBenefitVouchersAmountForItems' => [
                    200,
                    200,
                    200,
                ],
            ],
            'part of total applicable amount used' => [
                'itemData' => [
                    ItemTransfer::QUANTITY => 3,
                    ItemTransfer::USE_BENEFIT_VOUCHER => true,
                    ItemTransfer::TOTAL_USED_BENEFIT_VOUCHERS_AMOUNT => 350,
                    ItemTransfer::BENEFIT_VOUCHER_DEAL_DATA => [
                        BenefitVoucherDealDataTransfer::AMOUNT => 200,
                    ],
                ],
                'expectedBenefitVouchersAmountForItems' => [
                    200,
                    150,
                    null,
                ],
            ],
            'less than unit applicable amount used' => [
                'itemData' => [
                    ItemTransfer::QUANTITY => 3,
                    ItemTransfer::USE_BENEFIT_VOUCHER => true,
                    ItemTransfer::TOTAL_USED_BENEFIT_VOUCHERS_AMOUNT => 80,
                    ItemTransfer::BENEFIT_VOUCHER_DEAL_DATA => [
                        BenefitVoucherDealDataTransfer::AMOUNT => 200,
                    ],
                ],
                'expectedBenefitVouchersAmountForItems' => [
                    80,
                    null,
                    null,
                ],
            ],
        ];
    }

    /**
     * @return \Spryker\Zed\Sales\Business\Model\OrderItem\OrderItemTransformerInterface
     */
    private function createOrderItemTransformer(): OrderItemTransformerInterface
    {
        return new OrderItemTransformer([
            new BenefitVoucherAmountItemTransformerPlugin(),
        ]);
    }
}
