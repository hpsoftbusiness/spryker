<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductAttributeStorage\Persistence;

use Generated\Shared\Transfer\ProductAttributeKeysCollectionTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Pyz\Zed\ProductAttributeStorage\Persistence\ProductAttributeStoragePersistenceFactory getFactory()
 */
class ProductAttributeStorageEntityManager extends AbstractEntityManager implements ProductAttributeStorageEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductAttributeKeysCollectionTransfer $productAttributeKeysCollectionTransfer
     *
     * @return void
     */
    public function storeAttributeVisibilityData(
        ProductAttributeKeysCollectionTransfer $productAttributeKeysCollectionTransfer
    ): void {
        $storageEntityTransfer = $this->getFactory()
            ->createProductManagementAttributeVisibilityStorageQuery()
            ->findOneOrCreate();
        $storageEntityTransfer->setData($productAttributeKeysCollectionTransfer->toArray());
        $storageEntityTransfer->save();
    }
}
