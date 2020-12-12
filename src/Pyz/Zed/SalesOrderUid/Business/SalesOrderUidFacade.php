<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\SalesOrderUid\Business;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SpySalesOrderEntityTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Pyz\Zed\SalesOrderUid\Business\SalesOrderUidBusinessFactory getFactory()
 */
class SalesOrderUidFacade extends AbstractFacade implements SalesOrderUidFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SpySalesOrderEntityTransfer $salesOrderEntityTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\SpySalesOrderEntityTransfer
     */
    public function expandSalesOrderEntityTransferWithSalesOrderUid(
        SpySalesOrderEntityTransfer $salesOrderEntityTransfer,
        QuoteTransfer $quoteTransfer
    ): SpySalesOrderEntityTransfer {
        return $this->getFactory()->createOrderExpander()
            ->expandSalesOrderEntityTransferWithSalesOrderUid($salesOrderEntityTransfer, $quoteTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $salesOrderUid
     *
     * @return string|null
     */
    public function findCountryIso2CodeByUid(string $salesOrderUid): ?string
    {
        return $this->getFactory()->createCountryReader()
            ->findCountryIso2CodeByUid($salesOrderUid);
    }
}
