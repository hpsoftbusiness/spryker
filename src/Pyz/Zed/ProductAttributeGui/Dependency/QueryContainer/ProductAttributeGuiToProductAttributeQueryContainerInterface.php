<?php

namespace Pyz\Zed\ProductAttributeGui\Dependency\QueryContainer;

use Spryker\Zed\ProductAttributeGui\Dependency\QueryContainer\ProductAttributeGuiToProductAttributeQueryContainerInterface as SpyProductAttributeGuiToProductAttributeQueryContainerInterface;
use \Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttributeValueTranslationQuery;

interface ProductAttributeGuiToProductAttributeQueryContainerInterface extends SpyProductAttributeGuiToProductAttributeQueryContainerInterface
{
    /**
     * @return \Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttributeValueTranslationQuery
     */
    public function queryProductManagementAttributeValueTranslation(): SpyProductManagementAttributeValueTranslationQuery;
}
