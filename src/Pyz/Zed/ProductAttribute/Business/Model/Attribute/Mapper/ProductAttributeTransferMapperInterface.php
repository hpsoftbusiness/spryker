<?php

namespace Pyz\Zed\ProductAttribute\Business\Model\Attribute\Mapper;

use Generated\Shared\Transfer\ProductAttributeKeysCollectionTransfer;
use Propel\Runtime\Collection\ArrayCollection;
use Spryker\Zed\ProductAttribute\Business\Model\Attribute\Mapper\ProductAttributeTransferMapperInterface as SprykerProductAttributeTransferMapperInterface;

interface ProductAttributeTransferMapperInterface extends SprykerProductAttributeTransferMapperInterface
{
    /**
     * @param ArrayCollection $productAttributesKeyCollection
     *
     * @return ProductAttributeKeysCollectionTransfer
     */
    public function convertProductAttributeKeysCollection(
        ArrayCollection $productAttributesKeyCollection
    ): ProductAttributeKeysCollectionTransfer;
}
