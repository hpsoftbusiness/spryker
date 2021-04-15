<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\BenefitDeal\Business\Sales;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer;

class ItemPreSaveExpander implements ItemPreSaveExpanderInterface
{
    /**
     * @var \Pyz\Zed\BenefitDeal\Dependency\Plugin\ItemEntityExpanderPluginInterface[]
     */
    private $expanderPlugins;

    /**
     * @param \Pyz\Zed\BenefitDeal\Dependency\Plugin\ItemEntityExpanderPluginInterface[] $expanderPlugins
     */
    public function __construct(array $expanderPlugins)
    {
        $this->expanderPlugins = $expanderPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer $salesOrderItemEntity
     *
     * @return \Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer
     */
    public function expandOrderItem(
        QuoteTransfer $quoteTransfer,
        ItemTransfer $itemTransfer,
        SpySalesOrderItemEntityTransfer $salesOrderItemEntity
    ): SpySalesOrderItemEntityTransfer {
        foreach ($this->expanderPlugins as $expanderPlugin) {
            $expanderPlugin->expand($quoteTransfer, $itemTransfer, $salesOrderItemEntity);
        }

        return $salesOrderItemEntity;
    }
}
