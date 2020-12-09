<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\SalesInvoice\Business;

use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\SalesInvoice\Business\SalesInvoiceFacade as SprykerSalesInvoiceFacade;

/**
 * @method \Pyz\Zed\SalesInvoice\Business\SalesInvoiceBusinessFactory getFactory()
 */
class SalesInvoiceFacade extends SprykerSalesInvoiceFacade implements SalesInvoiceFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function expandOrderWithOrderInvoices(OrderTransfer $orderTransfer): OrderTransfer
    {
        return $this->getFactory()
            ->createOrderExpander()
            ->expandOrderWithOrderInvoices($orderTransfer);
    }
}
