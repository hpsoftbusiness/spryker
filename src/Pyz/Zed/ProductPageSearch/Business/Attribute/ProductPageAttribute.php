<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductPageSearch\Business\Attribute;

use Spryker\Zed\ProductPageSearch\Business\Attribute\ProductPageAttribute as SprykerProductPageAttribute;
use Spryker\Zed\ProductPageSearch\Business\Attribute\ProductPageAttributeInterface;

class ProductPageAttribute extends SprykerProductPageAttribute implements ProductPageAttributeInterface
{
    /**
     * @param array $attributeCollections
     *
     * @return array|void
     */
    protected function joinAttributeCollectionValues(array $attributeCollections)
    {
        $result = parent::joinAttributeCollectionValues($attributeCollections);

        return array_map(function (array $item) {
            if (is_array($item) && count($item) === 1) {
                return $item[0];
            }

            return $item;
        }, $result);
    }
}
