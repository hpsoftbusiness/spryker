<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Product\Business\Product;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\Product\Business\Product\ProductConcreteManager as SprykerProductConcreteManager;

class ProductConcreteManager extends SprykerProductConcreteManager
{
    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Orm\Zed\Product\Persistence\SpyProduct
     */
    protected function persistEntity(ProductConcreteTransfer $productConcreteTransfer)
    {
        $productConcreteTransfer
            ->requireSku()
            ->requireFkProductAbstract();

        $encodedAttributes = $this->attributeEncoder->encodeAttributes(
            $productConcreteTransfer->getAttributes()
        );

        $encodedHiddenAttributes = $this->attributeEncoder->encodeAttributes(
            $productConcreteTransfer->getHiddenAttributes()
        );

        $productConcreteEntity = $this->productQueryContainer
            ->queryProduct()
            ->filterByIdProduct($productConcreteTransfer->getIdProductConcrete())
            ->findOneOrCreate();

        $productConcreteData = $productConcreteTransfer->modifiedToArray();
        if (isset($productConcreteData[ProductConcreteTransfer::ATTRIBUTES])) {
            unset($productConcreteData[ProductConcreteTransfer::ATTRIBUTES]);
        }

        if (isset($productConcreteData['hidden_attributes'])) {
            unset($productConcreteData['hidden_attributes']);
        }

        $productConcreteEntity->fromArray($productConcreteData);
        $productConcreteEntity->setAttributes($encodedAttributes);
        $productConcreteEntity->setHiddenAttributes($encodedHiddenAttributes);

        $productConcreteEntity->save();

        return $productConcreteEntity;
    }
}
