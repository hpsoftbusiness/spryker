<?php

namespace Pyz\Zed\ProductAttribute\Business;

use Generated\Shared\Transfer\ProductAttributeKeysCollectionTransfer;
use Generated\Shared\Transfer\ProductManagementAttributeTransfer;
use Spryker\Zed\ProductAttribute\Business\ProductAttributeFacadeInterface as SprykerProductAttributeFacadeInterface;

interface ProductAttributeFacadeInterface extends SprykerProductAttributeFacadeInterface
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
     * Specification:
     * - Finds product management attribute by provided attribute key.
     *
     * @param string $key
     *
     * @return \Generated\Shared\Transfer\ProductManagementAttributeTransfer|null
     */
    public function findProductManagementAttributeByKey(string $key): ?ProductManagementAttributeTransfer;

    /**
     * Specification:
     * - Returns collection of product management super attributes.
     *
     * @return \Generated\Shared\Transfer\ProductManagementAttributeTransfer[]
     */
    public function getProductSuperAttributeCollection(): array;
}
