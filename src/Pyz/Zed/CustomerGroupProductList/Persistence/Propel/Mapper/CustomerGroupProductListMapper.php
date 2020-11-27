<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\CustomerGroupProductList\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\ProductListTransfer;
use Orm\Zed\ProductList\Persistence\SpyProductList;

class CustomerGroupProductListMapper
{
    /**
     * @param \Orm\Zed\ProductList\Persistence\SpyProductList $spyProductList
     * @param \Generated\Shared\Transfer\ProductListTransfer $productListTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListTransfer
     */
    public function mapProductListEntityToProductListTransfer(
        SpyProductList $spyProductList,
        ProductListTransfer $productListTransfer
    ): ProductListTransfer {
        return $productListTransfer->fromArray($spyProductList->toArray(), true);
    }
}
