<?php

namespace Pyz\Zed\ProductAttributeGui\Business\Modal\Writer;

use Spryker\Zed\ProductAttributeGui\Dependency\QueryContainer\ProductAttributeGuiToProductAttributeQueryContainerInterface;

class ProductAttributeWriter implements ProductAttributeWriterInterface
{
    /**
     * @var \Spryker\Zed\ProductAttributeGui\Dependency\QueryContainer\ProductAttributeGuiToProductAttributeQueryContainerInterface
     */
    private $attributeQueryContainer;

    /**
     * ProductAttributeWriter constructor.
     *
     * @param \Spryker\Zed\ProductAttributeGui\Dependency\QueryContainer\ProductAttributeGuiToProductAttributeQueryContainerInterface $attributeQueryContainer
     */
    public function __construct(ProductAttributeGuiToProductAttributeQueryContainerInterface $attributeQueryContainer)
    {
        $this->attributeQueryContainer = $attributeQueryContainer;
    }

    /**
     * @param int $idProductManagementAttribute
     */
    public function delete(int $idProductManagementAttribute): void
    {
        $productAttribute = $this->attributeQueryContainer
            ->queryProductManagementAttribute()
            ->findByIdProductManagementAttribute($idProductManagementAttribute);

        if($productAttribute) {
            $this->deleteProductAttributeValues($idProductManagementAttribute);

            $productAttribute->delete();
        }
    }

    /**
     * @param int $idProductManagementAttribute
     */
    public function deleteProductAttributeValues(int $idProductManagementAttribute): void
    {
        $this->attributeQueryContainer
            ->queryProductManagementAttributeValue()
            ->findByFkProductManagementAttribute($idProductManagementAttribute)
            ->delete();
    }
}
