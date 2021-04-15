<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Sales\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\PyzSalesOrderItemBenefitDealEntityTransfer;
use Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer;
use Orm\Zed\BenefitDeal\Persistence\PyzSalesOrderItemBenefitDeal;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Spryker\Zed\Sales\Persistence\Propel\Mapper\SalesOrderItemMapper as SprykerSalesOrderItemMapper;

class SalesOrderItemMapper extends SprykerSalesOrderItemMapper
{
    /**
     * @param \Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer $salesOrderItemEntity
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $salesOrderItem
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItem
     */
    public function mapSalesOrderItemEntityToSpySalesOrderItemEntity(
        SpySalesOrderItemEntityTransfer $salesOrderItemEntity,
        SpySalesOrderItem $salesOrderItem
    ): SpySalesOrderItem {
        $salesOrderItem = parent::mapSalesOrderItemEntityToSpySalesOrderItemEntity($salesOrderItemEntity, $salesOrderItem);

        foreach ($salesOrderItemEntity->getPyzSalesOrderItemBenefitDeals() as $pyzSalesOrderItemBenefitDeal) {
            $pyzSalesOrderItemBenefitDealEntity = $this->mapOrderItemBenefitDealTransferToEntity(
                $pyzSalesOrderItemBenefitDeal,
                new PyzSalesOrderItemBenefitDeal()
            );
            $salesOrderItem->addPyzSalesOrderItemBenefitDeal($pyzSalesOrderItemBenefitDealEntity);
        }

        return $salesOrderItem;
    }

    /**
     * @param \Generated\Shared\Transfer\PyzSalesOrderItemBenefitDealEntityTransfer $benefitDealEntityTransfer
     * @param \Orm\Zed\BenefitDeal\Persistence\PyzSalesOrderItemBenefitDeal $pyzSalesOrderItemBenefitDeal
     *
     * @return \Orm\Zed\BenefitDeal\Persistence\PyzSalesOrderItemBenefitDeal
     */
    private function mapOrderItemBenefitDealTransferToEntity(
        PyzSalesOrderItemBenefitDealEntityTransfer $benefitDealEntityTransfer,
        PyzSalesOrderItemBenefitDeal $pyzSalesOrderItemBenefitDeal
    ): PyzSalesOrderItemBenefitDeal {
        $pyzSalesOrderItemBenefitDeal->fromArray($benefitDealEntityTransfer->toArray());

        return $pyzSalesOrderItemBenefitDeal;
    }
}
