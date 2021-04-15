<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\BenefitDeal\Persistence;

use Generated\Shared\Transfer\PyzSalesOrderBenefitDealEntityTransfer;

interface BenefitDealRepositoryInterface
{
    /**
     * @param int $idSalesOrder
     *
     * @return \Generated\Shared\Transfer\PyzSalesOrderBenefitDealEntityTransfer|null
     */
    public function findSalesOrderBenefitDealByIdSalesOrder(int $idSalesOrder): ?PyzSalesOrderBenefitDealEntityTransfer;

    /**
     * @param int $idSalesOrderItem
     *
     * @return \Generated\Shared\Transfer\PyzSalesOrderItemBenefitDealEntityTransfer[]
     */
    public function findSalesOrderItemBenefitDealsByIdSalesOrderItem(int $idSalesOrderItem): array;
}
