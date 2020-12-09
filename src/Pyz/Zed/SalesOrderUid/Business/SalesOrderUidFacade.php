<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\SalesOrderUid\Business;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Pyz\Zed\SalesOrderUid\Business\SalesOrderUidBusinessFactory getFactory()
 */
class SalesOrderUidFacade extends AbstractFacade implements SalesOrderUidFacadeInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function expandSalesOrder(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        return $this->getFactory()->createOrderExpander()->expandSalesOrder($quoteTransfer);
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
