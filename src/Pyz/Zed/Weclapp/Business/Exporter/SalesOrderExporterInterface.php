<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Weclapp\Business\Exporter;

interface SalesOrderExporterInterface
{
    /**
     * @param int $salesOrderId
     *
     * @return void
     */
    public function exportSalesOrder(int $salesOrderId): void;
}