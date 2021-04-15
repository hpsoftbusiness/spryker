<?php

namespace Pyz\Client\ProductList;

use Generated\Shared\Transfer\CustomerProductListCollectionTransfer;

interface ProductListClientInterface
{
    /**
     * @return CustomerProductListCollectionTransfer
     */
    public function getDefaultCustomerProductListCollection(): CustomerProductListCollectionTransfer;
}
