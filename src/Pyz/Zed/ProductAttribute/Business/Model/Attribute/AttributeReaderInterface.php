<?php

namespace Pyz\Zed\ProductAttribute\Business\Model\Attribute;

use Generated\Shared\Transfer\ProductAttributeKeysCollectionTransfer;
use Spryker\Zed\ProductAttribute\Business\Model\Attribute\AttributeReaderInterface as SprykerAttributeReaderInterface;
use Generated\Shared\Transfer\ProductManagementAttributeTransfer;

interface AttributeReaderInterface extends SprykerAttributeReaderInterface
{
    /**
     * @param ProductAttributeKeysCollectionTransfer $productAttributeKeysCollectionTransfer
     *
     * @return ProductAttributeKeysCollectionTransfer
     */
    public function getKeysToShowOnPdp(
        ProductAttributeKeysCollectionTransfer $productAttributeKeysCollectionTransfer
    ): ProductAttributeKeysCollectionTransfer;

    /**
     * @param string $key
     *
     * @return \Generated\Shared\Transfer\ProductManagementAttributeTransfer|null
     */
    public function getAttributeByKey(string $key): ?ProductManagementAttributeTransfer;

    /**
     * @return \Generated\Shared\Transfer\ProductManagementAttributeTransfer[]
     */
    public function getProductSuperAttributeCollection(): array;
}
