<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
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
