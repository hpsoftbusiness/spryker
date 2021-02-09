<?php

namespace Pyz\Zed\ProductAttributeGui\Dependency\QueryContainer;

use Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttributeValueTranslationQuery;
use Spryker\Zed\ProductAttributeGui\Dependency\QueryContainer\ProductAttributeGuiToProductAttributeQueryContainerBridge as SpyProductAttributeGuiToProductAttributeQueryContainerBridge;

class ProductAttributeGuiToProductAttributeQueryContainerBridge extends SpyProductAttributeGuiToProductAttributeQueryContainerBridge implements ProductAttributeGuiToProductAttributeQueryContainerInterface
{
    /**
     * @return \Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttributeValueTranslationQuery
     */
    public function queryProductManagementAttributeValueTranslation(): SpyProductManagementAttributeValueTranslationQuery
    {
        return $this->productAttributeQueryContainer
            ->queryProductManagementAttributeValueTranslation();
    }
}
