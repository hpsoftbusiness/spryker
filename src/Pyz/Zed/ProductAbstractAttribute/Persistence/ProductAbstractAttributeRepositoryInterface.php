<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductAbstractAttribute\Persistence;

use Generated\Shared\Transfer\PyzProductAbstractAttributeEntityTransfer;

interface ProductAbstractAttributeRepositoryInterface
{
    /**
     * @param array $productAbstractIds
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstract[]
     */
    public function findProductAbstractEntitiesByIds(array $productAbstractIds): array;

    /**
     * @param int $productAbstractId
     *
     * @return \Generated\Shared\Transfer\PyzProductAbstractAttributeEntityTransfer
     */
    public function findProductAbstractAttributeByFkProductAbstract(int $productAbstractId): PyzProductAbstractAttributeEntityTransfer;
}
