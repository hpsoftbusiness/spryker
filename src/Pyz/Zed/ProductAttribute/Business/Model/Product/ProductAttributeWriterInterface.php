<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductAttribute\Business\Model\Product;

use Spryker\Zed\ProductAttribute\Business\Model\Product\ProductAttributeWriterInterface as SprykerProductAttributeWriterInterface;

interface ProductAttributeWriterInterface extends SprykerProductAttributeWriterInterface
{
    /**
     * @param int $idProduct
     * @param array $attributes
     * @param array|null $hiddenAttributes
     *
     * @return void
     */
    public function saveConcreteAttributes($idProduct, array $attributes, ?array $hiddenAttributes = null);
}
