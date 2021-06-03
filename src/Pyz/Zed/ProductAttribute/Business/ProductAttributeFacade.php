<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductAttribute\Business;

use Generated\Shared\Transfer\ProductAttributeKeysCollectionTransfer;
use Generated\Shared\Transfer\ProductManagementAttributeTransfer;
use Spryker\Zed\ProductAttribute\Business\ProductAttributeFacade as SprykerProductAttributeFacade;

/**
 * @method \Pyz\Zed\ProductAttribute\Business\ProductAttributeBusinessFactory getFactory()
 */
class ProductAttributeFacade extends SprykerProductAttributeFacade implements ProductAttributeFacadeInterface
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
            ->createAttributeReader()
            ->getKeysToShowOnPdp($productAttributeKeysCollectionTransfer);
    }

    /**
     * @param string $key
     *
     * @return \Generated\Shared\Transfer\ProductManagementAttributeTransfer|null
     */
    public function findProductManagementAttributeByKey(string $key): ?ProductManagementAttributeTransfer
    {
        return $this->getFactory()->createAttributeReader()->getAttributeByKey($key);
    }

    /**
     * @return \Generated\Shared\Transfer\ProductManagementAttributeTransfer[]
     */
    public function getProductSuperAttributeCollection(): array
    {
        return $this->getFactory()->createAttributeReader()->getProductSuperAttributeCollection();
    }
}
