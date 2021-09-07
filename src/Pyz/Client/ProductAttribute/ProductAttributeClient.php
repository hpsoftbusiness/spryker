<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\ProductAttribute;

use Generated\Shared\Transfer\ProductAttributeKeysCollectionTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Pyz\Client\ProductAttribute\ProductAttributeFactory getFactory()
 */
class ProductAttributeClient extends AbstractClient implements ProductAttributeClientInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductAttributeKeysCollectionTransfer $productAttributeKeysCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAttributeKeysCollectionTransfer
     */
    public function getKeysToShowOnPdp(
        ProductAttributeKeysCollectionTransfer $productAttributeKeysCollectionTransfer
    ): ProductAttributeKeysCollectionTransfer {
        return $this->getFactory()
            ->createProductAttributeZedStub()
            ->getKeysToShowOnPdp($productAttributeKeysCollectionTransfer);
    }
}
