<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Zed\Sales\Business;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\PyzSalesOrderBenefitDealEntityTransfer;
use Generated\Shared\Transfer\PyzSalesOrderItemBenefitDealEntityTransfer;
use Orm\Zed\BenefitDeal\Persistence\PyzSalesOrderBenefitDeal;
use Orm\Zed\BenefitDeal\Persistence\PyzSalesOrderItemBenefitDeal;
use Propel\Runtime\Map\TableMap;
use Pyz\Zed\MyWorldPayment\MyWorldPaymentConfig;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Zed
 * @group Sales
 * @group Business
 * @group Facade
 * @group SalesFacadeTest
 * Add your own group annotations below this line
 */
class SalesFacadeTest extends Unit
{
    private const DATA_SALES_ORDER_BENEFIT_DEAL = [
        PyzSalesOrderBenefitDealEntityTransfer::TOTAL_BENEFIT_VOUCHERS_AMOUNT => 500,
        PyzSalesOrderBenefitDealEntityTransfer::TOTAL_SHOPPING_POINTS_AMOUNT => 25.55,
    ];

    private const DATA_SALES_ORDER_ITEM_BENEFIT_DEAL_BV = [
        PyzSalesOrderItemBenefitDealEntityTransfer::UNIT_BENEFIT_PRICE => 1500,
        PyzSalesOrderItemBenefitDealEntityTransfer::BENEFIT_VOUCHER_AMOUNT => 500,
        PyzSalesOrderItemBenefitDealEntityTransfer::TYPE => MyWorldPaymentConfig::PAYMENT_METHOD_BENEFIT_VOUCHER_NAME,
    ];

    private const DATA_SALES_ORDER_ITEM_BENEFIT_DEAL_SP = [
        PyzSalesOrderItemBenefitDealEntityTransfer::UNIT_BENEFIT_PRICE => 1000,
        PyzSalesOrderItemBenefitDealEntityTransfer::SHOPPING_POINTS_AMOUNT => 25.55,
        PyzSalesOrderItemBenefitDealEntityTransfer::TYPE => MyWorldPaymentConfig::PAYMENT_METHOD_SHOPPING_POINTS,
    ];

    /**
     * @var \PyzTest\Zed\Sales\SalesBusinessTester
     */
    protected $tester;

    /**
     * @var \Pyz\Zed\Sales\Business\SalesFacadeInterface
     */
    private $sut;

    /**
     * @return void
     */
    protected function _before(): void
    {
        $this->sut = $this->tester->getFacade();
    }

    /**
     * @return void
     */
    public function testSalesOrderRetrievedHydratedWithBenefitDealData(): void
    {
        $salesOrderEntity = $this->tester->haveSalesOrderEntity([
            $this->buildItemTransfer(),
            $this->buildItemTransfer(),
        ]);

        $this->createBenefitDealForSalesOrder($salesOrderEntity->getIdSalesOrder());
        $this->createBenefitDealForSalesOrderItem(
            $salesOrderEntity->getItems()[0]->getIdSalesOrderItem(),
            self::DATA_SALES_ORDER_ITEM_BENEFIT_DEAL_BV
        );
        $this->createBenefitDealForSalesOrderItem(
            $salesOrderEntity->getItems()[1]->getIdSalesOrderItem(),
            self::DATA_SALES_ORDER_ITEM_BENEFIT_DEAL_SP
        );

        $orderTransfer = $this->sut->findOrderByIdSalesOrder($salesOrderEntity->getIdSalesOrder());
        self::assertNotNull($orderTransfer->getBenefitDeal());
        self::assertEquals(
            self::DATA_SALES_ORDER_BENEFIT_DEAL[PyzSalesOrderBenefitDealEntityTransfer::TOTAL_BENEFIT_VOUCHERS_AMOUNT],
            $orderTransfer->getBenefitDeal()->getTotalBenefitVouchersAmount()
        );
        self::assertEquals(
            self::DATA_SALES_ORDER_BENEFIT_DEAL[PyzSalesOrderBenefitDealEntityTransfer::TOTAL_SHOPPING_POINTS_AMOUNT],
            $orderTransfer->getBenefitDeal()->getTotalShoppingPointsAmount()
        );

        self::assertEquals(
            self::DATA_SALES_ORDER_ITEM_BENEFIT_DEAL_BV[PyzSalesOrderItemBenefitDealEntityTransfer::BENEFIT_VOUCHER_AMOUNT],
            $orderTransfer->getItems()[0]->getTotalUsedBenefitVouchersAmount()
        );
        self::assertEquals(
            self::DATA_SALES_ORDER_ITEM_BENEFIT_DEAL_BV[PyzSalesOrderItemBenefitDealEntityTransfer::UNIT_BENEFIT_PRICE],
            $orderTransfer->getItems()[0]->getUnitBenefitPrice()
        );
        self::assertTrue($orderTransfer->getItems()[0]->getUseBenefitVoucher());

        self::assertEquals(
            self::DATA_SALES_ORDER_ITEM_BENEFIT_DEAL_SP[PyzSalesOrderItemBenefitDealEntityTransfer::SHOPPING_POINTS_AMOUNT],
            $orderTransfer->getItems()[1]->getTotalUsedShoppingPointsAmount()
        );
        self::assertEquals(
            self::DATA_SALES_ORDER_ITEM_BENEFIT_DEAL_SP[PyzSalesOrderItemBenefitDealEntityTransfer::UNIT_BENEFIT_PRICE],
            $orderTransfer->getItems()[1]->getUnitBenefitPrice()
        );
    }

    /**
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    private function buildItemTransfer(): ItemTransfer
    {
        return (new ItemBuilder())->build();
    }

    /**
     * @param int $idSalesOrder
     *
     * @return void
     */
    private function createBenefitDealForSalesOrder(int $idSalesOrder): void
    {
        $entity = new PyzSalesOrderBenefitDeal();
        $entity->fromArray(self::DATA_SALES_ORDER_BENEFIT_DEAL, TableMap::TYPE_CAMELNAME);
        $entity->setFkSalesOrder($idSalesOrder);
        $entity->save();
    }

    /**
     * @param int $idSalesOrderItem
     * @param array $data
     *
     * @return void
     */
    private function createBenefitDealForSalesOrderItem(int $idSalesOrderItem, array $data): void
    {
        $entity = new PyzSalesOrderItemBenefitDeal();
        $entity->fromArray($data, TableMap::TYPE_CAMELNAME);
        $entity->setFkSalesOrderItem($idSalesOrderItem);
        $entity->save();
    }
}
