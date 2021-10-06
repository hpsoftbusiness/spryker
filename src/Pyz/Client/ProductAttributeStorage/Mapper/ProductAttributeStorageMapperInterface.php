<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\ProductAttributeStorage\Mapper;

use Generated\Shared\Transfer\ProductAttributeKeysCollectionTransfer;

interface ProductAttributeStorageMapperInterface
{
    /**
     * @param array $productAttributeKeys
     *
     * @return \Generated\Shared\Transfer\ProductAttributeKeysCollectionTransfer
     */
    public function mapArrayToProductAttributeKeysCollectionTransfer(
        array $productAttributeKeys
    ): ProductAttributeKeysCollectionTransfer;
}
