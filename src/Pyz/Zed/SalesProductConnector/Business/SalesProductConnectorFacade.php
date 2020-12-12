<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\SalesProductConnector\Business;

use Spryker\Zed\SalesProductConnector\Business\SalesProductConnectorFacade as SprykerSalesProductConnectorFacade;

/**
 * @method \Pyz\Zed\SalesProductConnector\Business\SalesProductConnectorBusinessFactory getFactory()
 */
class SalesProductConnectorFacade extends SprykerSalesProductConnectorFacade implements SalesProductConnectorFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    public function expandOrderItemsWithProductAttributes(array $itemTransfers): array
    {
        return $this->getFactory()
            ->createProductAttributesExpander()
            ->expandOrderItemsWithProductAttributes($itemTransfers);
    }
}
