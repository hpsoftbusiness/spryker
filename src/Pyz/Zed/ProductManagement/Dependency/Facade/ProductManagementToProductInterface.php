<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductManagement\Dependency\Facade;

use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToProductInterface as SprykerProductManagementToProductInterface;

interface ProductManagementToProductInterface extends SprykerProductManagementToProductInterface
{
    /**
     * @param int $idProductAbstract
     *
     * @return void
     */
    public function markProductAsRemoved(int $idProductAbstract): void;

    /**
     * @param int[] $idsProductAbstract
     *
     * @return int[]
     */
    public function findProductConcreteIdsByAbstractProductIds(array $idsProductAbstract): array;
}
