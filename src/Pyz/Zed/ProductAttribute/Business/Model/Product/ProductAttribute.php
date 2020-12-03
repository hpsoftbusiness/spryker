<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductAttribute\Business\Model\Product;

use Pyz\Zed\ProductAttribute\ProductAttributeConfig;
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

        return $this->generateProductConcreteAttributes(
            (array)$productTransfer->getAttributes(),
            (array)$productTransfer->getLocalizedAttributes(),
            (array)$productTransfer->getHiddenAttributes()
        );
    }

    /**
     * @param array $defaultAttributes
     * @param \Generated\Shared\Transfer\LocalizedAttributesTransfer[] $localizedAttributes
     * @param array|null $hiddenAttributes
     *
     * @return array
     */
    protected function generateProductConcreteAttributes(array $defaultAttributes, array $localizedAttributes, ?array $hiddenAttributes = null)
    {
        $result = [];
        foreach ($localizedAttributes as $localizedAttributeTransfer) {
            $localeName = $localizedAttributeTransfer->getLocale()->getLocaleName();
            $result[ProductAttributeConfig::KEY_ATTRIBUTES][$localeName] = $localizedAttributeTransfer->getAttributes();
        }

        $result[ProductAttributeConfig::KEY_ATTRIBUTES][ProductAttributeConfig::DEFAULT_LOCALE] = $defaultAttributes;
        $result[ProductAttributeConfig::KEY_HIDDEN_ATTRIBUTES][ProductAttributeConfig::DEFAULT_LOCALE] = $hiddenAttributes;

        return $result;
    }

    /**
     * @param int $idProduct
     * @param string|null $attributeKey
     *
     * @return array
     */
    public function getMetaAttributesForProduct($idProduct, ?string $attributeKey = null)
    {
        $values = $this->getProductAttributeValues($idProduct);

        return $this->productAttributeReader->getMetaAttributesByValues($values[$attributeKey]);
    }
}
