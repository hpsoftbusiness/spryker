<?php

namespace Pyz\Zed\Product\Persistence;

use Spryker\Zed\Product\Persistence\ProductQueryContainer as SpyProductQueryContainer;

class ProductQueryContainer extends SpyProductQueryContainer implements ProductQueryContainerInterface
{
    /**
     * @param string $attribute
     *
     * @throws \Propel\Runtime\Exception\PropelException
     * @return int
     */
    public function countUsesOfProductAttributeByProduct(string $attribute): int
    {
        return $this->queryProduct()
            ->select('id_product')
            ->where('attributes like "%\"' . $attribute . '\"%" ')
            ->count();
    }

    /**
     * @param string $attribute
     *
     * @throws \Propel\Runtime\Exception\PropelException
     * @return int
     */
    public function countUsesOfProductAttributeByAbstractProduct(string $attribute): int
    {
        return $this->queryProduct()
            ->select('id_product_abstract')
            ->where('attributes like "%\"' . $attribute . '\"%" ')
            ->count();
    }
}
