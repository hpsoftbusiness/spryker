<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductAttribute\Business\Model\Attribute;

use Generated\Shared\Transfer\ProductAttributeKeysCollectionTransfer;
use Generated\Shared\Transfer\ProductManagementAttributeTransfer;
use Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttribute;
use Spryker\Zed\ProductAttribute\Business\Model\Attribute\AttributeReader as SprykerAttributeReader;

class AttributeReader extends SprykerAttributeReader implements AttributeReaderInterface
{
    /**
     * @var \Pyz\Zed\ProductAttribute\Persistence\ProductAttributeQueryContainerInterface
     */
    protected $productAttributeQueryContainer;

    /**
     * @var \Pyz\Zed\ProductAttribute\Business\Model\Attribute\Mapper\ProductAttributeTransferMapperInterface
     */
    protected $productAttributeTransferMapper;

    /**
     * @param \Generated\Shared\Transfer\ProductAttributeKeysCollectionTransfer $productAttributeKeysCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAttributeKeysCollectionTransfer
     */
    public function getKeysToShowOnPdp(
        ProductAttributeKeysCollectionTransfer $productAttributeKeysCollectionTransfer
    ): ProductAttributeKeysCollectionTransfer {
        /** @var \Propel\Runtime\Collection\ArrayCollection $arrayCollection */
        $arrayCollection = $this->productAttributeQueryContainer
            ->queryProductAttributeCollection()
            ->filterByShowOnPdp(true)
            ->useSpyProductAttributeKeyQuery()
            ->filterByKey_In($productAttributeKeysCollectionTransfer->getKeys())
            ->endUse()
            ->select('SpyProductAttributeKey.Key')
            ->find();

        return $this->productAttributeTransferMapper->convertProductAttributeKeysCollection($arrayCollection);
    }

    /**
     * @param string $key
     *
     * @return \Generated\Shared\Transfer\ProductManagementAttributeTransfer|null
     */
    public function getAttributeByKey(string $key): ?ProductManagementAttributeTransfer
    {
        $attributeEntity = $this->getProductManagementAttributeEntityByKey($key);
        if (!$attributeEntity) {
            return null;
        }

        return $this->productAttributeTransferMapper->convertProductAttribute($attributeEntity);
    }

    /**
     * @return \Generated\Shared\Transfer\ProductManagementAttributeTransfer[]
     */
    public function getProductSuperAttributeCollection(): array
    {
        /** @var \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttribute[] $collection */
        $collection = $this->productAttributeQueryContainer
            ->queryProductAttributeCollection()
            ->joinWithSpyProductManagementAttributeValue()
            ->useSpyProductAttributeKeyQuery()
            ->filterByIsSuper(true)
            ->endUse()
            ->useSpyProductManagementAttributeValueQuery()
            ->leftJoinWithSpyProductManagementAttributeValueTranslation()
            ->endUse()
            ->find();

        return $this->productAttributeTransferMapper->convertProductAttributeCollection($collection);
    }

    /**
     * @param string $key
     *
     * @return \Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttribute|null
     */
    private function getProductManagementAttributeEntityByKey(string $key): ?SpyProductManagementAttribute
    {
        return $this->productAttributeQueryContainer->queryProductManagementAttributeByKey($key)->findOne();
    }
}
