<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductAttribute\Business\Model\Product;

use Spryker\Zed\ProductAttribute\Business\Model\Product\ProductAttribute as SprykerProductAttribute;

class ProductAttribute extends SprykerProductAttribute
{
    /**
     * @param int $idProduct
     *
     * @return array
     */
    public function getProductAttributeValues($idProduct)
    {
        $productTransfer = $this->productReader->getProductTransfer($idProduct);

        return $this->generateAttributes(
            array_merge(
                (array)$productTransfer->getAttributes(),
                (array)$productTransfer->getHiddenAttributes()
            ),
            (array)$productTransfer->getLocalizedAttributes()
        );
    }
}
