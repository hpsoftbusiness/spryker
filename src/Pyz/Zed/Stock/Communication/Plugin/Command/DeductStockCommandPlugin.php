<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Stock\Communication\Plugin\Command;

use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Pyz\Zed\Stock\StockConfig;
use Spryker\DecimalObject\Decimal;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject;
use Spryker\Zed\Oms\Dependency\Plugin\Command\CommandByOrderInterface;

/**
 * @method \Pyz\Zed\Stock\Business\StockFacadeInterface getFacade()
 * @method \Pyz\Zed\Stock\StockConfig getConfig()
 */
class DeductStockCommandPlugin extends AbstractPlugin implements CommandByOrderInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem[] $orderItems
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     * @param \Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject $data
     *
     * @return array
     */
    public function run(array $orderItems, SpySalesOrder $orderEntity, ReadOnlyArrayObject $data)
    {
        // TODO: more flexible stock deduction is required
        foreach ($orderItems as $orderItem) {
            if ($this->getFacade()->hasStockProduct($orderItem->getSku(), StockConfig::INTERNAL_WAREHOUSE_AUSTRIA)) {
                $this->getFacade()
                    ->decrementStockProduct($orderItem->getSku(), StockConfig::INTERNAL_WAREHOUSE_AUSTRIA, new Decimal(1));
            }

            if ($this->getFacade()->hasStockProduct($orderItem->getSku(), StockConfig::INTERNAL_WAREHOUSE_GERMANY)) {
                $this->getFacade()
                    ->decrementStockProduct($orderItem->getSku(), StockConfig::INTERNAL_WAREHOUSE_GERMANY, new Decimal(1));
            }
        }

        return [];
    }
}
