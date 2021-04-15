<?php

namespace Pyz\Zed\ProductList\Business;

use Generated\Shared\Transfer\CustomerProductListCollectionTransfer;
use Spryker\Zed\ProductList\Business\ProductListFacade as SprykerProductListFacade;

/**
 * @method \Pyz\Zed\ProductList\Business\ProductListBusinessFactory getFactory()
 */
class ProductListFacade extends SprykerProductListFacade implements ProductListFacadeInterface
{
    /**
     * @return CustomerProductListCollectionTransfer
     */
    public function getDefaultCustomerProductListCollection(): CustomerProductListCollectionTransfer
    {
        return $this->getFactory()
            ->createProductListReader()
            ->getDefaultCustomerProductListCollection();
    }

}
