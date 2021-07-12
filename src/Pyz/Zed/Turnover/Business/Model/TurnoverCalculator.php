<?php
declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Turnover\Business\Model;

use Generated\Shared\Transfer\TurnoverTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Pyz\Zed\Sales\Business\SalesFacadeInterface;

class TurnoverCalculator
{
    /**
     * @var \Pyz\Zed\Turnover\Communication\Plugin\TurnoverCalculatorPluginInterface[]
     */
    private $turnoverCalculatorPlugins;

    /**
     * @var \Pyz\Zed\Sales\Business\SalesFacadeInterface
     */
    private $salesFacade;

    /**
     * @param \Pyz\Zed\Turnover\Communication\Plugin\TurnoverCalculatorPluginInterface[] $turnoverCalculatorPlugins
     * @param \Pyz\Zed\Sales\Business\SalesFacadeInterface $salesFacade
     */
    public function __construct(
        array $turnoverCalculatorPlugins,
        SalesFacadeInterface $salesFacade
    ) {
        $this->turnoverCalculatorPlugins = $turnoverCalculatorPlugins;
        $this->salesFacade = $salesFacade;
    }

    /**
     * @param array $salesOrderItems
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $salesOrderEntity
     *
     * @return \Generated\Shared\Transfer\TurnoverTransfer
     */
    public function calculateTurnover(array $salesOrderItems, SpySalesOrder $salesOrderEntity): TurnoverTransfer
    {
        $turnoverTransfer = new TurnoverTransfer();
        $turnoverTransfer->setAmount(0);
        $orderTransfer = $this->getOrderTransfer($salesOrderEntity);
        foreach ($this->turnoverCalculatorPlugins as $turnoverCalculatorPlugin) {
            $turnoverTransfer = $turnoverCalculatorPlugin->calculateTurnover($turnoverTransfer, $orderTransfer, $salesOrderItems);
        }

        return $turnoverTransfer;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $salesOrderEntity
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    private function getOrderTransfer(SpySalesOrder $salesOrderEntity)
    {
        return $this->salesFacade->getOrderByIdSalesOrder($salesOrderEntity->getIdSalesOrder());
    }
}
