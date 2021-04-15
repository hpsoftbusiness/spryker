<?php

namespace Pyz\Zed\ProductList\Business;

use Generated\Shared\Transfer\CustomerProductListCollectionTransfer;
use Spryker\Zed\ProductList\Business\ProductListFacadeInterface as SprykerProductListFacadeInterface;

interface ProductListFacadeInterface extends SprykerProductListFacadeInterface
{
    /**
     * @return CustomerProductListCollectionTransfer
     */
    public function getDefaultCustomerProductListCollection(): CustomerProductListCollectionTransfer;
}
