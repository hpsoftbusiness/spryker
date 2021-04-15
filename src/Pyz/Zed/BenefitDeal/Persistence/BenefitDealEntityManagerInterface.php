<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\BenefitDeal\Persistence;

use Generated\Shared\Transfer\PyzSalesOrderBenefitDealEntityTransfer;

interface BenefitDealEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\PyzSalesOrderBenefitDealEntityTransfer $benefitDealEntityTransfer
     *
     * @return void
     */
    public function saveSalesOrderBenefitDeal(PyzSalesOrderBenefitDealEntityTransfer $benefitDealEntityTransfer): void;
}
