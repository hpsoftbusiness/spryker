<?php

namespace Pyz\Zed\ProductAttributeGui\Business\Modal\Writer;

use Pyz\Zed\ProductAttributeGui\Dependency\QueryContainer\ProductAttributeGuiToProductAttributeQueryContainerInterface;

class ProductAttributeWriter implements ProductAttributeWriterInterface
{
    /**
     * @var \Spryker\Zed\ProductAttributeGui\Dependency\QueryContainer\ProductAttributeGuiToProductAttributeQueryContainerInterface
     */
    private $attributeQueryContainer;

    /**
     * ProductAttributeWriter constructor.
     *
     * @param \Pyz\Zed\ProductAttributeGui\Dependency\QueryContainer\ProductAttributeGuiToProductAttributeQueryContainerInterface $attributeQueryContainer
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
            ->joinWithSpyProductAttributeKey()
            ->findByIdProductManagementAttribute($idProductManagementAttribute);

        if($productAttribute) {
            $this->deleteProductAttributeValuesWithTranslations($idProductManagementAttribute);

            $productAttribute->delete();
        }
    }

    /**
     * @param int $idProductManagementAttribute
     *
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function deleteProductAttributeValuesWithTranslations(int $idProductManagementAttribute): void
    {
        $productAttributeValues = $this->attributeQueryContainer
            ->queryProductManagementAttributeValue()
            ->findByFkProductManagementAttribute($idProductManagementAttribute);

        foreach($productAttributeValues as $productAttributeValue) {
            $this->deleteProductAttributeValuesTranslations($productAttributeValue->getIdProductManagementAttributeValue());

            $productAttributeValue->delete();
        }
    }

    /**
     * @param int $idProductManagementAttributeValue
     */
    public function deleteProductAttributeValuesTranslations(int $idProductManagementAttributeValue): void
    {
        $this->attributeQueryContainer
            ->queryProductManagementAttributeValueTranslation()
            ->findByFkProductManagementAttributeValue($idProductManagementAttributeValue)
            ->delete();
    }
}
