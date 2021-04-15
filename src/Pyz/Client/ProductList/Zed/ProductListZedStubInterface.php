<?php

namespace Pyz\Client\ProductList\Zed;

use Generated\Shared\Transfer\CustomerProductListCollectionTransfer;

interface ProductListZedStubInterface
{
    /**
     * @return CustomerProductListCollectionTransfer
     */
    public function getDefaultCustomerProductListCollection(): CustomerProductListCollectionTransfer;
}
