<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\ProductList;

use Generated\Shared\Transfer\CustomerProductListCollectionTransfer;

interface ProductListClientInterface
{
    /**
     * Get default customer product list collection
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\CustomerProductListCollectionTransfer
     */
    public function getDefaultCustomerProductListCollection(): CustomerProductListCollectionTransfer;
}
