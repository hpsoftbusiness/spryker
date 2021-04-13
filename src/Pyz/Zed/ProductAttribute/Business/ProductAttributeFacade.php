<?php

namespace Pyz\Zed\ProductAttribute\Business;

use Generated\Shared\Transfer\ProductAttributeKeysCollectionTransfer;
use Generated\Shared\Transfer\ProductManagementAttributeTransfer;
use Spryker\Zed\ProductAttribute\Business\ProductAttributeFacade as SprykerProductAttributeFacade;

class ProductAttributeFacade extends SprykerProductAttributeFacade implements ProductAttributeFacadeInterface
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
