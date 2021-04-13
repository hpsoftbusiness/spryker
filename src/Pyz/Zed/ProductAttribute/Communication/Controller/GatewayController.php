<?php

namespace Pyz\Zed\ProductAttribute\Communication\Controller;

use Generated\Shared\Transfer\ProductAttributeKeysCollectionTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractGatewayController;

class GatewayController extends AbstractGatewayController
{
    /**
     * @param ProductAttributeKeysCollectionTransfer $productAttributeKeysCollectionTransfer
     *
     * @return ProductAttributeKeysCollectionTransfer
     */
    public function getKeysToShowOnPdpAction(
        ProductAttributeKeysCollectionTransfer $productAttributeKeysCollectionTransfer
    ): ProductAttributeKeysCollectionTransfer {
        return $this->getFacade()->getKeysToShowOnPdp($productAttributeKeysCollectionTransfer);
    }
}
