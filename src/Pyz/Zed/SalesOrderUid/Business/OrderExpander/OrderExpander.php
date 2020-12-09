<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\SalesOrderUid\Business\OrderExpander;

use Generated\Shared\Transfer\QuoteTransfer;
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
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function expandSalesOrder(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        $countryToIdMap = $this->salesOrderUidConfig->getCountryToUidMap();
        $countryCode = $quoteTransfer->getShippingAddress()->getCountry()->getIso2Code();

        if (!empty($countryToIdMap[$countryCode])) {
            $quoteTransfer->setUid($countryToIdMap[$countryCode]);
        }

        return $quoteTransfer;
    }
}
