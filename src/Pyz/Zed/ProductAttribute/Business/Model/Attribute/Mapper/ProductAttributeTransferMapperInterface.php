<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductAttribute\Business\Model\Attribute\Mapper;

use Generated\Shared\Transfer\ProductAttributeKeysCollectionTransfer;
use Propel\Runtime\Collection\ArrayCollection;
use Spryker\Zed\ProductAttribute\Business\Model\Attribute\Mapper\ProductAttributeTransferMapperInterface as SprykerProductAttributeTransferMapperInterface;

interface ProductAttributeTransferMapperInterface extends SprykerProductAttributeTransferMapperInterface
{
    /**
     * @param \Propel\Runtime\Collection\ArrayCollection $productAttributesKeyCollection
     *
     * @return \Generated\Shared\Transfer\ProductAttributeKeysCollectionTransfer
     */
    public function convertProductAttributeKeysCollection(
        ArrayCollection $productAttributesKeyCollection
    ): ProductAttributeKeysCollectionTransfer;
}
