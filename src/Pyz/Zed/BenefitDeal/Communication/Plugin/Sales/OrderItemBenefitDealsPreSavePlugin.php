<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\BenefitDeal\Communication\Plugin\Sales;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SalesExtension\Dependency\Plugin\OrderItemExpanderPreSavePluginInterface;

/**
 * @method \Pyz\Zed\BenefitDeal\Business\BenefitDealFacadeInterface getFacade()
 * @method \Pyz\Zed\BenefitDeal\BenefitDealConfig getConfig()
 */
class OrderItemBenefitDealsPreSavePlugin extends AbstractPlugin implements OrderItemExpanderPreSavePluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer $salesOrderItemEntity
     *
     * @return \Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer
     */
    public function expandOrderItem(QuoteTransfer $quoteTransfer, ItemTransfer $itemTransfer, SpySalesOrderItemEntityTransfer $salesOrderItemEntity): SpySalesOrderItemEntityTransfer
    {
        return $this->getFacade()->expandOrderItemPreSave($quoteTransfer, $itemTransfer, $salesOrderItemEntity);
    }
}
