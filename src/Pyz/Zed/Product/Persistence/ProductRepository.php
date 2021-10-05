<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Product\Persistence;

use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Spryker\Zed\Product\Persistence\ProductRepository as SprykerProductRepository;

class ProductRepository extends SprykerProductRepository implements ProductRepositoryInterface
{
    /**
     * @param int[] $idsProductAbstract
     *
     * @return int[]
     */
    public function findProductConcreteIdsByAbstractProductIds(array $idsProductAbstract): array
    {
        $productConcreteQuery = $this->getFactory()
            ->createProductQuery();
        /** @var \Propel\Runtime\Collection\ObjectCollection|null $productConcreteIds */
        $productConcreteIds = $productConcreteQuery
            ->filterByFkProductAbstract_In($idsProductAbstract)
            ->select([SpyProductTableMap::COL_ID_PRODUCT])
            ->find();

        if (!$productConcreteIds) {
            return [];
        }

        return $productConcreteIds->getData();
    }
}
