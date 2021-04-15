<?php

namespace Pyz\Zed\ProductList\Communication\Controller;

use Generated\Shared\Transfer\CustomerProductListCollectionTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractGatewayController;

/**
 * @method \Pyz\Zed\ProductList\Business\ProductListFacadeInterface getFacade()
 */
class GatewayController extends AbstractGatewayController
{
    /**
     * @return CustomerProductListCollectionTransfer
     */
    public function getDefaultCustomerProductListCollectionAction(): CustomerProductListCollectionTransfer
    {
        return $this->getFacade()
            ->getDefaultCustomerProductListCollection();
    }
}
