<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Product\Communication\Plugin\Sales;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SalesExtension\Dependency\Plugin\OrderItemExpanderPluginInterface;

/**
 * @method \Pyz\Zed\Product\Business\ProductFacadeInterface getFacade()
 * @method \Pyz\Zed\Product\Persistence\ProductQueryContainerInterface getQueryContainer()
 */
class ProductConcreteOrderItemExpanderPlugin extends AbstractPlugin implements OrderItemExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands the order items with the product concretes using their skus.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    public function expand(array $itemTransfers): array
    {
        return $this->getFacade()->expandOrderItemsWithProductConcrete($itemTransfers);
    }
}
