<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Customer\Communication\Plugin\Sales;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SpySalesOrderEntityTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Sales\Dependency\Plugin\OrderExpanderPreSavePluginInterface;

/**
 * @method \Pyz\Zed\Customer\Communication\CustomerCommunicationFactory getFactory()
 * @method \Pyz\Zed\Customer\CustomerConfig getConfig()
 */
class CustomerOrderExpanderPreSavePlugin extends AbstractPlugin implements OrderExpanderPreSavePluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands sales order entity transfer with customer country ID.
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
        return $salesOrderEntityTransfer->setCustomerCountryId(
            $quoteTransfer->getCustomer()->getCountryId()
        );
    }
}
