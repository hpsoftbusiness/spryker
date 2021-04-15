<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\BenefitDeal\Dependency\Plugin;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer;

interface ItemEntityExpanderPluginInterface
{
    /**
     * Specification:
     * - Expands SpySalesOrderItemEntityTransfer with PyzOrderItemBenefitDealEntityTransfer based on benefit deals applied for the item.
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer $salesOrderItemEntity
     *
     * @return void
     */
    public function expand(
        QuoteTransfer $quoteTransfer,
        ItemTransfer $itemTransfer,
        SpySalesOrderItemEntityTransfer $salesOrderItemEntity
    ): void;
}
