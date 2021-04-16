<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\ProductList;

use Generated\Shared\Transfer\CustomerProductListCollectionTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Pyz\Client\ProductList\ProductListFactory getFactory()
 */
class ProductListClient extends AbstractClient implements ProductListClientInterface
{
    /**
     * @return \Generated\Shared\Transfer\CustomerProductListCollectionTransfer
     */
    public function getDefaultCustomerProductListCollection(): CustomerProductListCollectionTransfer
    {
        return $this->getFactory()
            ->createProductListZedStub()
            ->getDefaultCustomerProductListCollection();
    }
}
