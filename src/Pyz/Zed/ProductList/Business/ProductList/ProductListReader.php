<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductList\Business\ProductList;

use Generated\Shared\Transfer\CustomerProductListCollectionTransfer;
use Spryker\Zed\ProductList\Business\ProductList\ProductListReader as SprykerProductListReader;

class ProductListReader extends SprykerProductListReader implements ProductListReaderInterface
{
    /**
     * @return \Generated\Shared\Transfer\CustomerProductListCollectionTransfer
     */
    public function getDefaultCustomerProductListCollection(): CustomerProductListCollectionTransfer
    {
        $customerProductListCollectionTransfer = new CustomerProductListCollectionTransfer();
//        TODO:: investigate and uncomment
//        $customerProductListCollectionTransfer->addWhitelistId(CustomerGroupConstants::ID_CUSTOMER_MW);

        return $customerProductListCollectionTransfer;
    }
}
