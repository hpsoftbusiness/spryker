<?php
declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductCategoryStorage\Persistence;

use Orm\Zed\ProductCategory\Persistence\SpyProductCategoryQuery;
use Spryker\Zed\ProductCategoryStorage\Persistence\ProductCategoryStorageQueryContainerInterface as SprykerProductCategoryStorageQueryContainerInterface;

interface ProductCategoryStorageQueryContainerInterface extends SprykerProductCategoryStorageQueryContainerInterface
{
    /**
     * @param int[] $productAbstractIds
     *
     * @return \Orm\Zed\ProductCategory\Persistence\SpyProductCategoryQuery
     */
    public function queryAllProductCategoryWithCategoryNodes(array $productAbstractIds): SpyProductCategoryQuery;
}
