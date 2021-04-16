<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductList\Communication\Controller;

use Generated\Shared\Transfer\CustomerProductListCollectionTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractGatewayController;

/**
 * @method \Pyz\Zed\ProductList\Business\ProductListFacadeInterface getFacade()
 */
class GatewayController extends AbstractGatewayController
{
    /**
     * @return \Generated\Shared\Transfer\CustomerProductListCollectionTransfer
     */
    public function getDefaultCustomerProductListCollectionAction(): CustomerProductListCollectionTransfer
    {
        return $this->getFacade()
            ->getDefaultCustomerProductListCollection();
    }
}
