<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
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
