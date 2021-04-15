<?php

namespace Pyz\Client\ProductList;

use Generated\Shared\Transfer\CustomerProductListCollectionTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Pyz\Client\ProductList\ProductListFactory getFactory()
 */
class ProductListClient extends AbstractClient implements ProductListClientInterface
{
    /**
     * @return CustomerProductListCollectionTransfer
     */
    public function getDefaultCustomerProductListCollection(): CustomerProductListCollectionTransfer
    {
        return $this->getFactory()
            ->createProductListZedStub()
            ->getDefaultCustomerProductListCollection();
    }
}
