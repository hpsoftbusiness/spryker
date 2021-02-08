<?php

namespace Pyz\Zed\Product\Business\Product;

use Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery;

class CheckerOrderItem
{
    /**
     * @var SpySalesOrderItemQuery
     */
    private $spySalesOrderItemQuery;

    /**
     * CheckerOrderItem constructor.
     *
     * @param SpySalesOrderItemQuery $spySalesOrderItemQuery
     */
    public function __construct(SpySalesOrderItemQuery $spySalesOrderItemQuery)
    {
        $this->spySalesOrderItemQuery = $spySalesOrderItemQuery;
    }

    /**
     * @param string $sku
     * @return bool
     */
    public function hasProductOrderItemBySku(string $sku): bool
    {
        $this->spySalesOrderItemQuery->clear();
        $orderItemCollection = $this->spySalesOrderItemQuery->findBySku($sku);

        return !$orderItemCollection->isEmpty();
    }
}
