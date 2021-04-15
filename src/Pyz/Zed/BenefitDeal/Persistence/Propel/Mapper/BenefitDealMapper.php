<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\BenefitDeal\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\PyzSalesOrderBenefitDealEntityTransfer;
use Orm\Zed\BenefitDeal\Persistence\PyzSalesOrderBenefitDeal;

class BenefitDealMapper
{
    /**
     * @param \Generated\Shared\Transfer\PyzSalesOrderBenefitDealEntityTransfer $benefitDealEntityTransfer
     * @param \Orm\Zed\BenefitDeal\Persistence\PyzSalesOrderBenefitDeal $pyzSalesOrderBenefitDealEntity
     *
     * @return \Orm\Zed\BenefitDeal\Persistence\PyzSalesOrderBenefitDeal
     */
    public function mapEntityTransferToEntity(
        PyzSalesOrderBenefitDealEntityTransfer $benefitDealEntityTransfer,
        PyzSalesOrderBenefitDeal $pyzSalesOrderBenefitDealEntity
    ): PyzSalesOrderBenefitDeal {
        $pyzSalesOrderBenefitDealEntity->fromArray($benefitDealEntityTransfer->modifiedToArray());

        return $pyzSalesOrderBenefitDealEntity;
    }

    /**
     * @param \Orm\Zed\BenefitDeal\Persistence\PyzSalesOrderBenefitDeal $pyzSalesOrderBenefitDeal
     * @param \Generated\Shared\Transfer\PyzSalesOrderBenefitDealEntityTransfer $benefitDealEntityTransfer
     *
     * @return \Generated\Shared\Transfer\PyzSalesOrderBenefitDealEntityTransfer
     */
    public function mapEntityToTransferEntity(
        PyzSalesOrderBenefitDeal $pyzSalesOrderBenefitDeal,
        PyzSalesOrderBenefitDealEntityTransfer $benefitDealEntityTransfer
    ): PyzSalesOrderBenefitDealEntityTransfer {
        $benefitDealEntityTransfer->fromArray($pyzSalesOrderBenefitDeal->toArray(), true);

        return $benefitDealEntityTransfer;
    }
}
