<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\SalesOrderUid\Communication\Plugin\Sales;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SpySalesOrderEntityTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Sales\Dependency\Plugin\OrderExpanderPreSavePluginInterface;

/**
 * @method \Pyz\Zed\SalesOrderUid\Business\SalesOrderUidFacadeInterface getFacade()
 * @method \Pyz\Zed\SalesOrderUid\SalesOrderUidConfig getConfig()
 */
class UidOrderExpanderPreSavePlugin extends AbstractPlugin implements OrderExpanderPreSavePluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands sales order entity transfer with sales order UID.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SpySalesOrderEntityTransfer $salesOrderEntityTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\SpySalesOrderEntityTransfer
     */
    public function expand(
        SpySalesOrderEntityTransfer $salesOrderEntityTransfer,
        QuoteTransfer $quoteTransfer
    ): SpySalesOrderEntityTransfer {
        return $this->getFacade()
            ->expandSalesOrderEntityTransferWithSalesOrderUid($salesOrderEntityTransfer, $quoteTransfer);
    }
}
