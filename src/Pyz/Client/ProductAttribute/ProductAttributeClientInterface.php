<?php

namespace Pyz\Client\ProductAttribute;

use Generated\Shared\Transfer\ProductAttributeKeysCollectionTransfer;

interface ProductAttributeClientInterface
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
