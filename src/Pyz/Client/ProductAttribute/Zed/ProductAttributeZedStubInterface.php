<?php

namespace Pyz\Client\ProductAttribute\Zed;

use Generated\Shared\Transfer\ProductAttributeKeysCollectionTransfer;

interface ProductAttributeZedStubInterface
{
    /**
     * @param ProductAttributeKeysCollectionTransfer $productAttributeKeysCollectionTransfer
     *
     * @return ProductAttributeKeysCollectionTransfer
     */
    public function getKeysToShowOnPdp(
        ProductAttributeKeysCollectionTransfer $productAttributeKeysCollectionTransfer
    ): ProductAttributeKeysCollectionTransfer;
}
