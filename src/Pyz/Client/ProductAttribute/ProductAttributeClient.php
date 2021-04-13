<?php

namespace Pyz\Client\ProductAttribute;

use Generated\Shared\Transfer\ProductAttributeKeysCollectionTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Pyz\Client\ProductAttribute\ProductAttributeFactory getFactory()
 */
class ProductAttributeClient extends AbstractClient implements ProductAttributeClientInterface
{
    /**
     * @param ProductAttributeKeysCollectionTransfer $productAttributeKeysCollectionTransfer
     *
     * @return ProductAttributeKeysCollectionTransfer
     */
    public function getKeysToShowOnPdp(
        ProductAttributeKeysCollectionTransfer $productAttributeKeysCollectionTransfer
    ): ProductAttributeKeysCollectionTransfer {
        return $this->getFactory()
            ->createProductAttributeZedStub()
            ->getKeysToShowOnPdp($productAttributeKeysCollectionTransfer);
    }
}
