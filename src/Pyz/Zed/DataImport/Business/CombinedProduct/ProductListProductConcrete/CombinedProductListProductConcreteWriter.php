<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\CombinedProduct\ProductListProductConcrete;

use Orm\Zed\ProductList\Persistence\SpyProductListProductConcreteQuery;
use Orm\Zed\ProductList\Persistence\SpyProductListQuery;
use Pyz\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\PublishAwareStep;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\ProductList\Dependency\ProductListEvents;

class CombinedProductListProductConcreteWriter extends PublishAwareStep implements DataImportStepInterface
{
    public const BULK_SIZE = 50;

    public const COLUMN_CONCRETE_SKU = 'concrete_sku';
    public const KEY_ID_PRODUCT_CONCRETE = 'id_product_concrete';

    protected const KEY_ATTRIBUTES = 'attributes';

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet)
    {
        $idProductConcrete = $dataSet[CombinedProductListProductConcreteWriter::KEY_ID_PRODUCT_CONCRETE];

        foreach ($dataSet[static::KEY_ATTRIBUTES] as $productListKey => $attributeValue) {
            $this->persistProductListProductConcreteEntity(
                $idProductConcrete,
                $productListKey,
                $attributeValue
            );
        }

        $this->addPublishEvents(
            ProductListEvents::PRODUCT_LIST_PRODUCT_CONCRETE_PUBLISH,
            $idProductConcrete
        );
    }

    /**
     * @param int $idProductConcrete
     * @param string $productListKey
     * @param string $attributeValue
     *
     * @throws \Pyz\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return void
     */
    protected function persistProductListProductConcreteEntity(
        int $idProductConcrete,
        string $productListKey,
        string $attributeValue
    ): void {
        if (!$this->getIsProductListAssigned($attributeValue)) {
            return;
        }

        $productListEntity = SpyProductListQuery::create()
            ->findOneByKey($productListKey);

        if (!$productListEntity) {
            throw new EntityNotFoundException(
                sprintf('Product list with key "%s" not found.', $productListKey)
            );
        }

        $productListProductConcreteEntity = SpyProductListProductConcreteQuery::create()
            ->filterByFkProductList($productListEntity->getIdProductList())
            ->filterByFkProduct($idProductConcrete)
            ->findOneOrCreate();

        if ($productListProductConcreteEntity->isNew()) {
            $productListProductConcreteEntity->save();
        }
    }

    /**
     * @param string $attributeValue
     *
     * @return bool
     */
    protected function getIsProductListAssigned(string $attributeValue): bool
    {
        return $attributeValue === 'TRUE';
    }
}
