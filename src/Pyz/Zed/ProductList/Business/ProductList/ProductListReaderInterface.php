<?php

namespace Pyz\Zed\ProductList\Business\ProductList;

use Generated\Shared\Transfer\CustomerProductListCollectionTransfer;
use Spryker\Zed\ProductList\Business\ProductList\ProductListReaderInterface as SprykerProductListReaderInterface;

interface ProductListReaderInterface extends SprykerProductListReaderInterface
{
    /**
     * @return CustomerProductListCollectionTransfer
     */
    public function getDefaultCustomerProductListCollection(): CustomerProductListCollectionTransfer;
}
