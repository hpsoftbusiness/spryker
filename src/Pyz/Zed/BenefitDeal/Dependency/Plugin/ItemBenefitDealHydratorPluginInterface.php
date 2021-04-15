<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\BenefitDeal\Dependency\Plugin;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\PyzSalesOrderItemBenefitDealEntityTransfer;

interface ItemBenefitDealHydratorPluginInterface
{
    /**
     * Specification:
     * - Hydrates ItemTransfer with data from PyzSalesOrderItemBenefitDealEntityTransfer.
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\PyzSalesOrderItemBenefitDealEntityTransfer $benefitDealEntityTransfer
     *
     * @return void
     */
    public function hydrateItem(
        ItemTransfer $itemTransfer,
        PyzSalesOrderItemBenefitDealEntityTransfer $benefitDealEntityTransfer
    ): void;

    /**
     * Specification:
     * - This value is being compared to `type` field in PyzSalesOrderItemBenefitDealEntityTransfer to determine
     *  plugins applicability for the benefit deal.
     *
     * @return string
     */
    public function getType(): string;
}
