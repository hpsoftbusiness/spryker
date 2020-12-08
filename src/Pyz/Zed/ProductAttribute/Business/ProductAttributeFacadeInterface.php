<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
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
     * @param string|array|null $hiddenAttributes
     *
     * @return void
     */
    public function saveConcreteAttributes($idProduct, array $attributes, ?array $hiddenAttributes = null);
}
