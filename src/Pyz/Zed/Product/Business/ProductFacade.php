<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Product\Business;

use Spryker\Zed\Product\Business\ProductFacade as SprykerProductFacade;

/**
 * @method \Pyz\Zed\Product\Business\ProductBusinessFactory getFactory()
 * @method \Pyz\Zed\Product\Persistence\ProductRepositoryInterface getRepository()
 */
class ProductFacade extends SprykerProductFacade implements ProductFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    public function expandOrderItemsWithProductConcrete(array $itemTransfers): array
    {
        return $this->getFactory()
            ->createOrderItemExpander()
            ->expandOrderItemsWithProductConcrete($itemTransfers);
    }

    /**
     * @param int $idProductAbstract
     *
     * @return void
     */
    public function markProductAsRemoved(int $idProductAbstract): void
    {
        $this->getFactory()->createProductRemover()->markAbstractProductAsRemoved($idProductAbstract);
    }

    /**
     * @param int[] $productAbstractIds
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer[]
     */
    public function getRawProductAbstractsByProductAbstractIds(array $productAbstractIds): array
    {
        return $this->getRepository()->getRawProductAbstractsByProductAbstractIds($productAbstractIds);
    }

    /**
     * @param int[] $idsProductAbstract
     *
     * @return int[]
     */
    public function findProductConcreteIdsByAbstractProductIds(array $idsProductAbstract): array
    {
        return $this->getRepository()->findProductConcreteIdsByAbstractProductIds($idsProductAbstract);
    }
}
