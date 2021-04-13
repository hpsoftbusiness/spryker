<?php

namespace Pyz\Zed\ProductAttribute\Business\Model\Attribute\Mapper;

use Generated\Shared\Transfer\ProductAttributeKeysCollectionTransfer;
use Propel\Runtime\Collection\ArrayCollection;
use Spryker\Zed\ProductAttribute\Business\Model\Attribute\Mapper\ProductAttributeTransferMapper as SprykerProductAttributeTransferMapper;

class ProductAttributeTransferMapper extends SprykerProductAttributeTransferMapper implements ProductAttributeTransferMapperInterface
{
    /**
     * @param ArrayCollection $productAttributesKeyCollection
     *
     * @return ProductAttributeKeysCollectionTransfer
     */
    public function convertProductAttributeKeysCollection(
        ArrayCollection $productAttributesKeyCollection
    ): ProductAttributeKeysCollectionTransfer {
        $productAttributesKeyCollectionTransfer = new ProductAttributeKeysCollectionTransfer();
        $productAttributesKeyCollectionTransfer->setKeys($productAttributesKeyCollection->toArray());

        return $productAttributesKeyCollectionTransfer;
    }

}
