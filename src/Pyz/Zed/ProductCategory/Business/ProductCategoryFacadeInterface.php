<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductCategory\Business;

use Spryker\Zed\ProductCategory\Business\ProductCategoryFacadeInterface as SprykerProductCategoryFacadeInterface;

interface ProductCategoryFacadeInterface extends SprykerProductCategoryFacadeInterface
{
    /**
     * Specification:
     * - Expands the order items with the categories using product abstract IDs.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    public function expandOrderItemsWithCategories(array $itemTransfers): array;
}
