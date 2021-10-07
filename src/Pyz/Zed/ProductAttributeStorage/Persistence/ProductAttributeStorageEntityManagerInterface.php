<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductAttributeStorage\Persistence;

use Generated\Shared\Transfer\ProductAttributeKeysCollectionTransfer;

interface ProductAttributeStorageEntityManagerInterface
{
    /**
     * Get keys to Shown On Pdp
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductAttributeKeysCollectionTransfer $productAttributeKeysCollectionTransfer
     *
     * @return void
     */
    public function storeAttributeVisibilityData(
        ProductAttributeKeysCollectionTransfer $productAttributeKeysCollectionTransfer
    ): void;
}
