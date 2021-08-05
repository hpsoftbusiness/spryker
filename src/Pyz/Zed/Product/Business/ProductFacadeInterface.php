<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Product\Business;

use Spryker\Zed\Product\Business\ProductFacadeInterface as SprykerProductFacadeInterface;

interface ProductFacadeInterface extends SprykerProductFacadeInterface
{
    /**
     * Specification:
     * - Expands the order items with the product concretes using their skus.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    public function expandOrderItemsWithProductConcrete(array $itemTransfers): array;

    /**
     * @param int $idProductAbstract
     *
     * @return void
     */
    public function markProductAsRemoved(int $idProductAbstract): void;

    /**
     * @param int[] $productAbstractIds
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer[]
     */
    public function getRawProductAbstractsByProductAbstractIds(array $productAbstractIds): array;

    /**
     * Specification:
     * - Finds product concrete ids by product abstract ids.
     *
     * @api
     *
     * @param int[] $idsProductAbstract
     *
     * @return int[]
     */
    public function findProductConcreteIdsByAbstractProductIds(array $idsProductAbstract): array;
}
