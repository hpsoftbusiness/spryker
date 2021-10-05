<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\ProductAttributeStorage;

use Generated\Shared\Transfer\ProductAttributeKeysCollectionTransfer;

interface ProductAttributeStorageClientInterface
{
    /**
     * Get keys to show on PDP
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\ProductAttributeKeysCollectionTransfer
     */
    public function getKeysToShowOnPdp(): ProductAttributeKeysCollectionTransfer;
}
