<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Pyz\Zed\ProductAttribute\Business;

use Spryker\Zed\ProductAttribute\Business\ProductAttributeFacadeInterface as SprykerProductAttributeFacadeInterface;

interface ProductAttributeFacadeInterface extends SprykerProductAttributeFacadeInterface
{
    /**
     * @param int $idProduct
     * @param string|null $attributeKey
     *
     * @return array
     */
    public function getMetaAttributesForProduct($idProduct, ?string $attributeKey = null);

    /**
     * @param int $idProduct
     * @param array $attributes
     * @param string|null $hiddenAttributes
     *
     * @return void
     */
    public function saveConcreteAttributes($idProduct, array $attributes, ?array $hiddenAttributes = null);
}
