<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\BenefitDeal\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\PyzSalesOrderItemBenefitDealEntityTransfer;
use Orm\Zed\BenefitDeal\Persistence\PyzSalesOrderItemBenefitDeal;

class ItemBenefitDealMapper
{
    /**
     * @param \Orm\Zed\BenefitDeal\Persistence\PyzSalesOrderItemBenefitDeal[] $pyzSaleOrderItemBenefitDeals
     *
     * @return \Generated\Shared\Transfer\PyzSalesOrderItemBenefitDealEntityTransfer[]
     */
    public function mapEntityCollectionToTransfers(array $pyzSaleOrderItemBenefitDeals): array
    {
        return array_map(
            function (PyzSalesOrderItemBenefitDeal $pyzSalesOrderItemBenefitDeal) {
                return $this->mapEntityToTransfer(
                    $pyzSalesOrderItemBenefitDeal,
                    new PyzSalesOrderItemBenefitDealEntityTransfer()
                );
            },
            $pyzSaleOrderItemBenefitDeals
        );
    }

    /**
     * @param \Orm\Zed\BenefitDeal\Persistence\PyzSalesOrderItemBenefitDeal $pyzSalesOrderItemBenefitDealEntity
     * @param \Generated\Shared\Transfer\PyzSalesOrderItemBenefitDealEntityTransfer $benefitDealEntityTransfer
     *
     * @return \Generated\Shared\Transfer\PyzSalesOrderItemBenefitDealEntityTransfer
     */
    public function mapEntityToTransfer(
        PyzSalesOrderItemBenefitDeal $pyzSalesOrderItemBenefitDealEntity,
        PyzSalesOrderItemBenefitDealEntityTransfer $benefitDealEntityTransfer
    ): PyzSalesOrderItemBenefitDealEntityTransfer {
        return $benefitDealEntityTransfer->fromArray($pyzSalesOrderItemBenefitDealEntity->toArray(), true);
    }
}
