<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Pyz\Zed\SalesProductConnector\Communication\Plugin\Sales;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Product\Business\ProductFacade;
use Spryker\Zed\SalesExtension\Dependency\Plugin\OrderItemExpanderPluginInterface;

/**
 * @method \Spryker\Zed\SalesProductConnector\Business\SalesProductConnectorFacadeInterface getFacade()
 * @method \Spryker\Zed\SalesProductConnector\Persistence\SalesProductConnectorQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\SalesProductConnector\SalesProductConnectorConfig getConfig()
 */
class ProductAttributesOrderItemExpanderPlugin extends AbstractPlugin implements OrderItemExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Hydrates product ids (abstract / concrete) into an order items based on their skus.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    public function expand(array $itemTransfers): array
    {
        foreach ($itemTransfers as $itemTransfer) {
//            /** @var \Generated\Shared\Transfer\ItemTransfer $itemTransfers */
            $productConcrete = (new ProductFacade())->getProductConcrete($itemTransfer->getSku());
            $itemTransfer->setConcreteAttributes($productConcrete->getAttributes());
        }
//        dd($itemTransfers);

        return  $itemTransfers;
        //TODO expand item with attributes
//        return $this->getFacade()->expandOrderItemsWithProductIds($itemTransfers);
    }
}
