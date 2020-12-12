<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\SalesOrderUid\Business;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SpySalesOrderEntityTransfer;

interface SalesOrderUidFacadeInterface
{
    /**
     * Specification:
     * - Expands sales order entity transfer with sales order UID.
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
    ): SpySalesOrderEntityTransfer;

    /**
     * Specification:
     * - Finds a country iso2code assigned to the given sales order UID.
     *
     * @api
     *
     * @param string $salesOrderUid
     *
     * @return string|null
     */
    public function findCountryIso2CodeByUid(string $salesOrderUid): ?string;
}
