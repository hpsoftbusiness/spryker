<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Weclapp\Business\Exporter;

use Pyz\Client\Weclapp\WeclappClientInterface;
use Spryker\Zed\Sales\Business\SalesFacadeInterface;

class SalesOrderExporter implements SalesOrderExporterInterface
{
    /**
     * @var \Pyz\Client\Weclapp\WeclappClientInterface
     */
    protected $weclappClient;

    /**
     * @var \Spryker\Zed\Sales\Business\SalesFacadeInterface
     */
    protected $salesFacade;

    /**
     * @param \Pyz\Client\Weclapp\WeclappClientInterface $weclappClient
     * @param \Spryker\Zed\Sales\Business\SalesFacadeInterface $salesFacade
     */
    public function __construct(
        WeclappClientInterface $weclappClient,
        SalesFacadeInterface $salesFacade
    ) {
        $this->weclappClient = $weclappClient;
        $this->salesFacade = $salesFacade;
    }

    /**
     * @param int $salesOrderId
     *
     * @return void
     */
    public function exportSalesOrder(int $salesOrderId): void
    {
        $orderTransfer = $this->salesFacade->findOrderByIdSalesOrder($salesOrderId);
        if ($orderTransfer) {
            $this->weclappClient->postSalesOrder($orderTransfer);
        }
    }
}
