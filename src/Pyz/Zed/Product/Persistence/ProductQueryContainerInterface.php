<?php

namespace Pyz\Zed\Product\Persistence;

use Spryker\Zed\Product\Persistence\ProductQueryContainerInterface as SpyProductQueryContainerInterface;

interface ProductQueryContainerInterface extends SpyProductQueryContainerInterface
{
    /**
     * @param string $attribute
     *
     * @return int
     */
    public function countUsesOfProductAttributeByProduct(string $attribute): int;

    /**
     * @param string $attribute
     *
     * @return int
     */
    public function countUsesOfProductAttributeByAbstractProduct(string $attribute): int;
}
