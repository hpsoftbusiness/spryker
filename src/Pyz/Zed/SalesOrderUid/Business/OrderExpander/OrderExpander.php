<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\SalesOrderUid\Business\OrderExpander;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SpySalesOrderEntityTransfer;
use Pyz\Zed\SalesOrderUid\SalesOrderUidConfig;

class OrderExpander implements OrderExpanderInterface
{
    /**
     * @var \Pyz\Zed\SalesOrderUid\SalesOrderUidConfig
     */
    protected $salesOrderUidConfig;

    /**
     * @param \Pyz\Zed\SalesOrderUid\SalesOrderUidConfig $salesOrderUidConfig
     */
    public function __construct(SalesOrderUidConfig $salesOrderUidConfig)
    {
        $this->salesOrderUidConfig = $salesOrderUidConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\SpySalesOrderEntityTransfer $salesOrderEntityTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\SpySalesOrderEntityTransfer
     */
    public function expandSalesOrderEntityTransferWithSalesOrderUid(
        SpySalesOrderEntityTransfer $salesOrderEntityTransfer,
        QuoteTransfer $quoteTransfer
    ): SpySalesOrderEntityTransfer {
        $countryToIdMap = $this->salesOrderUidConfig->getCountryToUidMap();
        $countryCode = $quoteTransfer->getShippingAddress()->getCountry()->getIso2Code();

        if (empty($countryToIdMap[$countryCode])) {
            return $salesOrderEntityTransfer;
        }

        return $salesOrderEntityTransfer->setUid($countryToIdMap[$countryCode]);
    }
}
