<?php

namespace Pyz\Zed\ProductList\Business\ProductList;

use Generated\Shared\Transfer\CustomerProductListCollectionTransfer;
use Pyz\Shared\CustomerGroup\CustomerGroupConstants;
use Spryker\Zed\ProductList\Business\ProductList\ProductListReader as SprykerProductListReader;

class ProductListReader extends SprykerProductListReader implements ProductListReaderInterface
{
    /**
     * @return CustomerProductListCollectionTransfer
     */
    public function getDefaultCustomerProductListCollection(): CustomerProductListCollectionTransfer
    {
        $customerProductListCollectionTransfer = new CustomerProductListCollectionTransfer();
        $customerProductListCollectionTransfer->addWhitelistId(CustomerGroupConstants::ID_CUSTOMER_MW);

        return $customerProductListCollectionTransfer;
    }

}
