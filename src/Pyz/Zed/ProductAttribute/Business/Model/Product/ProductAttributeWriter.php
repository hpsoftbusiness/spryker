<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductAttribute\Business\Model\Product;

use Generated\Shared\Transfer\ProductAttributeKeyTransfer;
use Spryker\Zed\ProductAttribute\Business\Model\Product\ProductAttributeWriter as SprykerProductAttributeWriter;

class ProductAttributeWriter extends SprykerProductAttributeWriter
{
    /**
     * @param array $attributes
     *
     * @return array
     */
    protected function getAttributesDataToSave(array $attributes)
    {
        $attributeData = [];

        foreach ($attributes as $attribute) {
            $key = $attribute[ProductAttributeKeyTransfer::KEY];
            $localeCode = $attribute['locale_code'];
            $value = $this->sanitizeString($attribute['value']);

            if (strpos($key, 'sellable_') !== false) {
                $value = $value ?: '0';
            }

            if ($value === '') {
                continue;
            }

            $attributeData[$localeCode][$key] = $value;
        }

        return $attributeData;
    }
}
