<?php

namespace Pyz\Zed\ProductAttributeGui\Dependency\QueryContainer;

use Spryker\Zed\ProductAttribute\Persistence\ProductAttributeQueryContainerInterface;

interface ProductAttributeGuiToProductAttributeQueryContainerInterface extends ProductAttributeQueryContainerInterface
{
    /**
     * @param int $idProductManagementAttribute
     */
    public function deleteProductAttributeValuesWithTranslations(int $idProductManagementAttribute): void;

    /**
     * @param int $idProductManagementAttributeValue
     */
    public function deleteProductAttributeValuesTranslations(int $idProductManagementAttributeValue): void;

    /**
     * @param int $idProductManagementAttribute
     */
    public function deleteProductAttribute(int $idProductManagementAttribute): void;
}
