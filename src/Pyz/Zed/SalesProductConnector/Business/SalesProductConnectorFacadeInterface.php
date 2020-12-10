<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\SalesProductConnector\Business;

use Spryker\Zed\SalesProductConnector\Business\SalesProductConnectorFacadeInterface as SprykerSalesProductConnectorFacadeInterface;

interface SalesProductConnectorFacadeInterface extends SprykerSalesProductConnectorFacadeInterface
{
    /**
     * Specification:
     * - Hydrates concrete product attributes into an order items based on their skus.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    public function expandOrderItemsWithProductAttributes(array $itemTransfers): array;
}
