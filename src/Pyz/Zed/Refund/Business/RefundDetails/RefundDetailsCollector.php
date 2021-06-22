<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Refund\Business\RefundDetails;

use Pyz\Zed\Sales\Business\SalesFacadeInterface;

class RefundDetailsCollector implements RefundDetailsCollectorInterface
{
    /**
     * @var \Pyz\Zed\Sales\Business\SalesFacadeInterface
     */
    private $salesFacade;

    /**
     * @var \Pyz\Zed\Refund\Dependency\Plugin\RefundDetailsCollectorPluginInterface[]
     */
    private $collectorPlugins;

    /**
     * @param \Pyz\Zed\Sales\Business\SalesFacadeInterface $salesFacade
     * @param \Pyz\Zed\Refund\Dependency\Plugin\RefundDetailsCollectorPluginInterface[] $collectorPlugins
     */
    public function __construct(SalesFacadeInterface $salesFacade, array $collectorPlugins)
    {
        $this->salesFacade = $salesFacade;
        $this->collectorPlugins = $collectorPlugins;
    }

    /**
     * @param int $idSalesOrder
     *
     * @return \Generated\Shared\Transfer\RefundDetailCollectionTransfer[]
     */
    public function collectBySalesOrderId(int $idSalesOrder): array
    {
        $orderTransfer = $this->salesFacade->getOrderByIdSalesOrder($idSalesOrder);
        $refundDetails = [];

        foreach ($this->collectorPlugins as $collectorPlugin) {
            $refundDetails = array_merge($refundDetails, $collectorPlugin->collect($orderTransfer));
        }

        return $refundDetails;
    }
}
