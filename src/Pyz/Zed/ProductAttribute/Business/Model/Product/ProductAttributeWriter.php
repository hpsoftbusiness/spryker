<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Pyz\Zed\ProductAttribute\Business\Model\Product;

use ArrayObject;
use Generated\Shared\Transfer\ProductAttributeKeyTransfer;
use Spryker\Zed\ProductAttribute\Business\Model\Product\ProductAttributeWriter as SprykerProductAttributeWriter;

class ProductAttributeWriter extends SprykerProductAttributeWriter implements ProductAttributeWriterInterface
{
    /**
     * @param int $idProduct
     * @param array $attributes
     * @param array|null $hiddenAttributes
     *
     * @return void
     */
    public function saveConcreteAttributes($idProduct, array $attributes, ?array $hiddenAttributes = null)
    {
        $productConcreteTransfer = $this->productReader->getProductTransfer($idProduct);
        $attributesToSave = $this->getAttributesDataToSave($attributes);
        $hiddenAttributesToSave = $this->getHiddenAttributesDataToSave($hiddenAttributes);
        $nonLocalizedAttributes = $this->getNonLocalizedAttributes($attributesToSave);
        $nonLocalizedHiddenAttributes = $this->getNonLocalizedAttributes($hiddenAttributesToSave);

        $productConcreteTransfer
            ->setAttributes(
                $nonLocalizedAttributes
            )
            ->setHiddenAttributes(
                $nonLocalizedHiddenAttributes
            );

        $localizedAttributes = $this->updateLocalizedAttributeTransfers($attributesToSave, (array)$productConcreteTransfer->getLocalizedAttributes());
        $productConcreteTransfer->setLocalizedAttributes(new ArrayObject($localizedAttributes));

        $this->productFacade->saveProductConcrete($productConcreteTransfer);
        $this->productFacade->touchProductConcrete($productConcreteTransfer->getIdProductConcrete());
    }

    /**
     * @param array $attributes
     *
     * @return array
     */
    protected function getHiddenAttributesDataToSave(array $attributes)
    {
        $attributeData = [];

        foreach ($attributes as $attribute) {
            $key = $attribute[ProductAttributeKeyTransfer::KEY];
            $localeCode = $attribute['locale_code'];
            $value = $attribute['value'];
            $attributeData[$localeCode][$key] = $value;
        }

        return $attributeData;
    }
}
