<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductAttribute\Persistence;

use Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttributeQuery;
use Spryker\Zed\ProductAttribute\Persistence\ProductAttributeQueryContainerInterface as SprykerProductAttributeQueryContainerInterface;

interface ProductAttributeQueryContainerInterface extends SprykerProductAttributeQueryContainerInterface
{
    /**
     * @param string $key
     *
     * @return \Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttributeQuery
     */
    public function queryProductManagementAttributeByKey(string $key): SpyProductManagementAttributeQuery;
}
