<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

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
