<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductList\Business;

use Generated\Shared\Transfer\CustomerProductListCollectionTransfer;
use Spryker\Zed\ProductList\Business\ProductListFacade as SprykerProductListFacade;

/**
 * @method \Pyz\Zed\ProductList\Business\ProductListBusinessFactory getFactory()
 */
class ProductListFacade extends SprykerProductListFacade implements ProductListFacadeInterface
{
    /**
     * @return \Generated\Shared\Transfer\CustomerProductListCollectionTransfer
     */
    public function getDefaultCustomerProductListCollection(): CustomerProductListCollectionTransfer
    {
        return $this->getFactory()
            ->createProductListReader()
            ->getDefaultCustomerProductListCollection();
    }
}
