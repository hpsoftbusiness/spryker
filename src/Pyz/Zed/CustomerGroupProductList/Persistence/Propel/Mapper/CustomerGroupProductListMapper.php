<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\CustomerGroupProductList\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\CustomerGroupToProductListTransfer;
use Generated\Shared\Transfer\ProductListTransfer;
use Orm\Zed\CustomerGroupProductList\Persistence\PyzCustomerGroupToProductList;
use Orm\Zed\ProductList\Persistence\SpyProductList;

class CustomerGroupProductListMapper
{
    /**
     * @param \Generated\Shared\Transfer\CustomerGroupToProductListTransfer $customerGroupToProductListTransfer
     * @param \Orm\Zed\CustomerGroupProductList\Persistence\PyzCustomerGroupToProductList $customerGroupToProductListEntity
     *
     * @return \Orm\Zed\CustomerGroupProductList\Persistence\PyzCustomerGroupToProductList
     */
    public function mapCustomerGroupProductListTransferToCustomerGroupProductListEntity(
        CustomerGroupToProductListTransfer $customerGroupToProductListTransfer,
        PyzCustomerGroupToProductList $customerGroupToProductListEntity
    ): PyzCustomerGroupToProductList {
        $customerGroupToProductListEntity->setFkCustomerGroup($customerGroupToProductListTransfer->getIdCustomerGroup());
        $customerGroupToProductListEntity->setFkProductList($customerGroupToProductListTransfer->getIdProductList());

        return $customerGroupToProductListEntity;
    }

    /**
     * @param \Orm\Zed\CustomerGroupProductList\Persistence\PyzCustomerGroupToProductList $customerGroupToProductListEntity
     * @param \Generated\Shared\Transfer\CustomerGroupToProductListTransfer $customerGroupToProductListTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerGroupToProductListTransfer
     */
    public function mapCustomerGroupProductListEntityToCustomerGroupProductListTransfer(
        PyzCustomerGroupToProductList $customerGroupToProductListEntity,
        CustomerGroupToProductListTransfer $customerGroupToProductListTransfer
    ): CustomerGroupToProductListTransfer {
        $customerGroupToProductListTransfer->setIdCustomerGroup($customerGroupToProductListEntity->getFkCustomerGroup());

        return $customerGroupToProductListTransfer->setIdProductList($customerGroupToProductListEntity->getFkProductList());
    }

    /**
     * @param \Orm\Zed\ProductList\Persistence\SpyProductList $productListEntity
     * @param \Generated\Shared\Transfer\ProductListTransfer $productListTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListTransfer
     */
    public function mapProductListEntityToProductListTransfer(
        SpyProductList $productListEntity,
        ProductListTransfer $productListTransfer
    ): ProductListTransfer {
        return $productListTransfer->fromArray($productListEntity->toArray(), true);
    }
}
