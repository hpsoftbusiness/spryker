<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductManagement\Dependency\Facade;

use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToProductBridge as SprykerProductManagementToProductBridge;

/**
 * @SuppressWarnings(PHPMD)
 * @property \Pyz\Zed\Product\Business\ProductFacadeInterface $productFacade
 */
class ProductManagementToProductBridge extends SprykerProductManagementToProductBridge implements ProductManagementToProductInterface
{
    /**
     * @param int $idProductAbstract
     *
     * @return void
     */
    public function markProductAsRemoved(int $idProductAbstract): void
    {
        /** @var \Pyz\Zed\Product\Business\ProductFacadeInterface $productFacade */
        $productFacade = $this->productFacade;
        $productFacade->markProductAsRemoved($idProductAbstract);
    }

    /**
     * @param int[] $idsProductAbstract
     *
     * @return int[]
     */
    public function findProductConcreteIdsByAbstractProductIds(array $idsProductAbstract): array
    {
        return $this->productFacade->findProductConcreteIdsByAbstractProductIds($idsProductAbstract);
    }
}
