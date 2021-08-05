<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Product\Persistence;

use Spryker\Zed\Product\Persistence\ProductRepositoryInterface as SprykerProductRepositoryInterface;

interface ProductRepositoryInterface extends SprykerProductRepositoryInterface
{
    /**
     * @param int[] $idsProductAbstract
     *
     * @return int[]
     */
    public function findProductConcreteIdsByAbstractProductIds(array $idsProductAbstract): array;
}
