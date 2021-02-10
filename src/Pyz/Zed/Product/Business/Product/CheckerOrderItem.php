<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Product\Business\Product;

use Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery;

class CheckerOrderItem
{
    /**
     * @var \Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery
     */
    private $spySalesOrderItemQuery;

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery $spySalesOrderItemQuery
     */
    public function __construct(SpySalesOrderItemQuery $spySalesOrderItemQuery)
    {
        $this->spySalesOrderItemQuery = $spySalesOrderItemQuery;
    }

    /**
     * @param string $sku
     *
     * @return bool
     */
    public function hasProductOrderItemBySku(string $sku): bool
    {
        $this->spySalesOrderItemQuery->clear();
        $orderItemCollection = $this->spySalesOrderItemQuery->findBySku($sku);

        return !$orderItemCollection->isEmpty();
    }
}
