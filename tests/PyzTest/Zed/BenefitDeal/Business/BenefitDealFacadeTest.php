<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Zed\BenefitDeal\Business;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\DataBuilder\OrderBuilder;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\DataBuilder\SaveOrderBuilder;
use Generated\Shared\Transfer\BenefitVoucherDealDataTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Generated\Shared\Transfer\ShoppingPointsDealTransfer;
use Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer;
use Orm\Zed\BenefitDeal\Persistence\PyzSalesOrderBenefitDeal;
use Orm\Zed\BenefitDeal\Persistence\PyzSalesOrderBenefitDealQuery;
use Orm\Zed\BenefitDeal\Persistence\PyzSalesOrderItemBenefitDeal;
use Pyz\Zed\BenefitDeal\Business\BenefitDealFacadeInterface;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Zed
 * @group BenefitDeal
 * @group Business
 * @group Facade
 * @group BenefitDealFacadeTest
 * Add your own group annotations below this line
 */
class BenefitDealFacadeTest extends Unit
{
    private const PAYMENT_NAME_BENEFIT_VOUCHER = 'BenefitVouchers';
    private const PAYMENT_NAME_SHOPPING_POINTS = 'ShoppingPoints';

    private const ITEM_SKU_WITH_BENEFIT_VOUCHER = 'BV_0001';
    private const ITEM_SKU_WITH_SHOPPING_POINTS = 'SP_0001';

    /**
     * @var \PyzTest\Zed\BenefitDeal\BenefitDealBusinessTester
     */
    protected $tester;

    /**
     * @var \Pyz\Zed\BenefitDeal\Business\BenefitDealFacadeInterface
     */
    private $sut;

    /**
     * @return void
     */
    protected function _before(): void
    {
        $this->sut = $this->getFacade();
    }

    /**
     * @return void
     */
    public function testSalesOrderBenefitDealDataSaved(): void
    {
        $salesOrderEntity = $this->tester->haveSalesOrderEntity();
        $saveOrderTransfer = $this->buildSaveOrderTransfer([
            SaveOrderTransfer::ID_SALES_ORDER => $salesOrderEntity->getIdSalesOrder(),
        ]);
        $quoteTransfer = $this->buildQuoteTransfer([
            QuoteTransfer::TOTAL_USED_SHOPPING_POINTS_AMOUNT => 10,
            QuoteTransfer::TOTAL_USED_BENEFIT_VOUCHERS_AMOUNT => 25,
        ]);

        $this->sut->saveSalesOrderBenefitDealFromQuote($saveOrderTransfer, $quoteTransfer);

        $benefitDealEntity = PyzSalesOrderBenefitDealQuery::create()
            ->findOneByFkSalesOrder($salesOrderEntity->getIdSalesOrder());

        self::assertNotNull($benefitDealEntity);
        self::assertEquals(10, $benefitDealEntity->getTotalShoppingPointsAmount());
        self::assertEquals(25, $benefitDealEntity->getTotalBenefitVouchersAmount());
    }

    /**
     * @return void
     */
    public function testSalesOrderBenefitDealNotSavedIfBenefitDealsWereNotAppliedOnQuote(): void
    {
        $salesOrderEntity = $this->tester->haveSalesOrderEntity();
        $saveOrderTransfer = $this->buildSaveOrderTransfer([
            SaveOrderTransfer::ID_SALES_ORDER => $salesOrderEntity->getIdSalesOrder(),
        ]);
        $quoteTransfer = $this->buildQuoteTransfer([
            QuoteTransfer::TOTAL_USED_SHOPPING_POINTS_AMOUNT => 0,
        ]);

        $this->sut->saveSalesOrderBenefitDealFromQuote($saveOrderTransfer, $quoteTransfer);

        $benefitDealEntity = PyzSalesOrderBenefitDealQuery::create()
            ->findOneByFkSalesOrder($salesOrderEntity->getIdSalesOrder());

        self::assertNull($benefitDealEntity);
    }

    /**
     * @return void
     */
    public function testOrderHydratedWithBenefitDealData(): void
    {
        $salesOrderEntity = $this->tester->haveSalesOrderEntity();
        $salesOrderBenefitDealEntity = new PyzSalesOrderBenefitDeal();
        $salesOrderBenefitDealEntity->setFkSalesOrder($salesOrderEntity->getIdSalesOrder());
        $salesOrderBenefitDealEntity->setTotalShoppingPointsAmount(10);
        $salesOrderBenefitDealEntity->setTotalBenefitVouchersAmount(25);
        $salesOrderBenefitDealEntity->save();

        $orderTransfer = $this->buildOrderTransfer([
            OrderTransfer::ID_SALES_ORDER => $salesOrderEntity->getIdSalesOrder(),
        ]);

        $this->sut->hydrateOrderWithBenefitDeal($orderTransfer);

        self::assertNotNull($orderTransfer->getBenefitDeal());
        self::assertEquals(
            10,
            $orderTransfer->getBenefitDeal()->getTotalShoppingPointsAmount()
        );
        self::assertEquals(
            25,
            $orderTransfer->getBenefitDeal()->getTotalBenefitVouchersAmount()
        );
    }

    /**
     * @return void
     */
    public function testItemEntityTransferExpandedWithBenefitVoucherDealDataBeforeSaving(): void
    {
        $quoteTransfer = $this->buildQuoteTransfer();
        $itemTransfer = $this->buildItemTransfer([
            ItemTransfer::USE_BENEFIT_VOUCHER => true,
            ItemTransfer::BENEFIT_VOUCHER_DEAL_DATA => [
                BenefitVoucherDealDataTransfer::IS_STORE => true,
            ],
            ItemTransfer::TOTAL_USED_BENEFIT_VOUCHERS_AMOUNT => 25,
            ItemTransfer::ORIGIN_UNIT_GROSS_PRICE => 125,
        ]);
        $salesOrderItemEntityTransfer = new SpySalesOrderItemEntityTransfer();

        $this->sut->expandOrderItemPreSave($quoteTransfer, $itemTransfer, $salesOrderItemEntityTransfer);

        self::assertCount(1, $salesOrderItemEntityTransfer->getPyzSalesOrderItemBenefitDeals());
        self::assertEquals(
            self::PAYMENT_NAME_BENEFIT_VOUCHER,
            $salesOrderItemEntityTransfer->getPyzSalesOrderItemBenefitDeals()[0]->getType()
        );
        self::assertEquals(
            25,
            $salesOrderItemEntityTransfer->getPyzSalesOrderItemBenefitDeals()[0]->getBenefitVoucherAmount()
        );
        self::assertEquals(
            125,
            $salesOrderItemEntityTransfer->getPyzSalesOrderItemBenefitDeals()[0]->getOriginUnitGrossPrice()
        );
    }

    /**
     * @return void
     */
    public function testItemEntityTransferNotExpandedBeforeSavingIfBenefitVoucherDealDataMissing(): void
    {
        $quoteTransfer = $this->buildQuoteTransfer();
        $itemTransfer = $this->buildItemTransfer([
            ItemTransfer::USE_BENEFIT_VOUCHER => true,
            ItemTransfer::TOTAL_USED_BENEFIT_VOUCHERS_AMOUNT => 25,
            ItemTransfer::ORIGIN_UNIT_GROSS_PRICE => 125,
        ]);
        $salesOrderItemEntityTransfer = new SpySalesOrderItemEntityTransfer();

        $this->sut->expandOrderItemPreSave($quoteTransfer, $itemTransfer, $salesOrderItemEntityTransfer);

        self::assertEmpty($salesOrderItemEntityTransfer->getPyzSalesOrderItemBenefitDeals());
    }

    /**
     * @return void
     */
    public function testItemEntityTransferExpandedWithShoppingPointsDealDataBeforeSaving(): void
    {
        $quoteTransfer = $this->buildQuoteTransfer();
        $itemTransfer = $this->buildItemTransfer([
            ItemTransfer::USE_SHOPPING_POINTS => true,
            ItemTransfer::SHOPPING_POINTS_DEAL => [
                ShoppingPointsDealTransfer::IS_ACTIVE => true,
                ShoppingPointsDealTransfer::SHOPPING_POINTS_QUANTITY => 5,
            ],
            ItemTransfer::TOTAL_USED_SHOPPING_POINTS_AMOUNT => 10,
            ItemTransfer::ORIGIN_UNIT_GROSS_PRICE => 125,
        ]);
        $salesOrderItemEntityTransfer = new SpySalesOrderItemEntityTransfer();

        $this->sut->expandOrderItemPreSave($quoteTransfer, $itemTransfer, $salesOrderItemEntityTransfer);

        self::assertCount(1, $salesOrderItemEntityTransfer->getPyzSalesOrderItemBenefitDeals());
        self::assertEquals(
            self::PAYMENT_NAME_SHOPPING_POINTS,
            $salesOrderItemEntityTransfer->getPyzSalesOrderItemBenefitDeals()[0]->getType()
        );
        self::assertEquals(
            10,
            $salesOrderItemEntityTransfer->getPyzSalesOrderItemBenefitDeals()[0]->getShoppingPointsAmount()
        );
        self::assertEquals(
            125,
            $salesOrderItemEntityTransfer->getPyzSalesOrderItemBenefitDeals()[0]->getOriginUnitGrossPrice()
        );
    }

    /**
     * @return void
     */
    public function testItemEntityTransferNotExpandedBeforeSavingIfShoppingPointsDealDataMissing(): void
    {
        $quoteTransfer = $this->buildQuoteTransfer();
        $itemTransfer = $this->buildItemTransfer([
            ItemTransfer::USE_SHOPPING_POINTS => true,
            ItemTransfer::TOTAL_USED_SHOPPING_POINTS_AMOUNT => 10,
            ItemTransfer::ORIGIN_UNIT_GROSS_PRICE => 125,
        ]);
        $salesOrderItemEntityTransfer = new SpySalesOrderItemEntityTransfer();

        $this->sut->expandOrderItemPreSave($quoteTransfer, $itemTransfer, $salesOrderItemEntityTransfer);

        self::assertEmpty($salesOrderItemEntityTransfer->getPyzSalesOrderItemBenefitDeals());
    }

    /**
     * @return void
     */
    public function testOrderItemsHydratedWithBenefitData(): void
    {
        /**
         * @var \Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
         */
        $itemTransfers = [
            $this->buildItemTransfer([
                ItemTransfer::SKU => self::ITEM_SKU_WITH_BENEFIT_VOUCHER,
            ]),
            $this->buildItemTransfer([
                ItemTransfer::SKU => self::ITEM_SKU_WITH_SHOPPING_POINTS,
            ]),
            $this->buildItemTransfer(),
        ];

        $salesOrderEntity = $this->tester->haveSalesOrderEntity($itemTransfers);

        foreach ($salesOrderEntity->getItems() as $index => $itemEntity) {
            $itemBenefitDealEntity = new PyzSalesOrderItemBenefitDeal();
            $itemBenefitDealEntity->setFkSalesOrderItem($itemEntity->getIdSalesOrderItem());

            if ($itemEntity->getSku() === self::ITEM_SKU_WITH_BENEFIT_VOUCHER) {
                $itemBenefitDealEntity->setType(self::PAYMENT_NAME_BENEFIT_VOUCHER);
                $itemBenefitDealEntity->setBenefitVoucherAmount(25);
                $itemBenefitDealEntity->setOriginUnitGrossPrice(150);
                $itemBenefitDealEntity->save();
            }

            if ($itemEntity->getSku() === self::ITEM_SKU_WITH_SHOPPING_POINTS) {
                $itemBenefitDealEntity->setType(self::PAYMENT_NAME_SHOPPING_POINTS);
                $itemBenefitDealEntity->setShoppingPointsAmount(10);
                $itemBenefitDealEntity->setOriginUnitGrossPrice(200);
                $itemBenefitDealEntity->save();
            }

            $itemTransfers[$index]->setIdSalesOrderItem($itemEntity->getIdSalesOrderItem());
        }

        $this->sut->expandOrderItems($itemTransfers);

        self::assertEquals(150, $itemTransfers[0]->getOriginUnitGrossPrice());
        self::assertEquals(25, $itemTransfers[0]->getTotalUsedBenefitVouchersAmount());

        self::assertEquals(200, $itemTransfers[1]->getOriginUnitGrossPrice());
        self::assertEquals(10, $itemTransfers[1]->getTotalUsedShoppingPointsAmount());

        self::assertNull($itemTransfers[2]->getOriginUnitGrossPrice());
        self::assertNull($itemTransfers[2]->getTotalUsedShoppingPointsAmount());
        self::assertNull($itemTransfers[2]->getTotalUsedBenefitVouchersAmount());
    }

    /**
     * @return void
     */
    public function testBenefitDealQuoteEqualizedTransfersBenefitUsageFlagsToResultQuote(): void
    {
        $sourceQuoteTransfer = $this->buildQuoteTransfer([
            QuoteTransfer::ITEMS => [
                [
                    ItemTransfer::SKU => self::ITEM_SKU_WITH_SHOPPING_POINTS,
                    ItemTransfer::USE_SHOPPING_POINTS => true,
                ],
                [
                    ItemTransfer::SKU => self::ITEM_SKU_WITH_BENEFIT_VOUCHER,
                    ItemTransfer::USE_BENEFIT_VOUCHER => true,
                ],
                [
                    ItemTransfer::SKU => 'test',
                ],
            ],
        ]);

        $resultQuoteTransfer = $this->buildQuoteTransfer([
            QuoteTransfer::ITEMS => [
                [
                    ItemTransfer::SKU => self::ITEM_SKU_WITH_SHOPPING_POINTS,
                ],
                [
                    ItemTransfer::SKU => self::ITEM_SKU_WITH_BENEFIT_VOUCHER,
                ],
                [
                    ItemTransfer::SKU => 'test',
                ],
            ],
        ]);

        $this->sut->equalizeQuoteItemsBenefitDealUsageFlags($resultQuoteTransfer, $sourceQuoteTransfer);

        self::assertNull($resultQuoteTransfer->getItems()[0]->getUseBenefitVoucher());
        self::assertTrue($resultQuoteTransfer->getItems()[0]->getUseShoppingPoints());

        self::assertTrue($resultQuoteTransfer->getItems()[1]->getUseBenefitVoucher());
        self::assertNull($resultQuoteTransfer->getItems()[1]->getUseShoppingPoints());

        self::assertNull($resultQuoteTransfer->getItems()[2]->getUseBenefitVoucher());
        self::assertNull($resultQuoteTransfer->getItems()[2]->getUseShoppingPoints());
    }

    /**
     * @param array $quoteOverride
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    private function buildQuoteTransfer(array $quoteOverride = []): QuoteTransfer
    {
        return (new QuoteBuilder($quoteOverride))->build();
    }

    /**
     * @param array $override
     *
     * @return \Generated\Shared\Transfer\SaveOrderTransfer
     */
    private function buildSaveOrderTransfer(array $override = []): SaveOrderTransfer
    {
        return (new SaveOrderBuilder($override))->build();
    }

    /**
     * @param array $override
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    private function buildOrderTransfer(array $override = []): OrderTransfer
    {
        return (new OrderBuilder($override))->build();
    }

    /**
     * @param array $override
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    private function buildItemTransfer(array $override = []): ItemTransfer
    {
        return (new ItemBuilder($override))->build();
    }

    /**
     * @return \Pyz\Zed\BenefitDeal\Business\BenefitDealFacadeInterface
     */
    private function getFacade(): BenefitDealFacadeInterface
    {
        return $this->tester->getLocator()->benefitDeal()->facade();
    }
}
