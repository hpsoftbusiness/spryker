<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\Model\ProductImage\Writer;

use Pyz\Zed\DataImport\Business\CombinedProduct\ProductImage\CombinedProductImageHydratorStep;
use Pyz\Zed\DataImport\Business\Model\ProductImage\ProductImageHydratorStep;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface;

class ProductImageBulkPdoMariaDbDataSetWriter extends AbstractProductImageBulkDataSetWriter implements DataSetWriterInterface
{
    /**
     * @return void
     */
    protected function persistProductAbstractImageSet(): void
    {
        $productImageSetNames = $this->dataFormatter->getCollectionDataByKey(static::$productImageDataCollection, CombinedProductImageHydratorStep::COLUMN_PRODUCT_IMAGE_SET_KEY);
        $fkLocaleIds = $this->dataFormatter->getCollectionDataByKey(static::$productImageDataCollection, ProductImageHydratorStep::KEY_IMAGE_SET_FK_LOCALE);
        $fkProductAbstractIds = $this->dataFormatter->getCollectionDataByKey(
            static::$productImageDataCollection,
            ProductImageHydratorStep::KEY_FK_PRODUCT_ABSTRACT
        );
        $fkProductConcreteIds = $this->dataFormatter->getCollectionDataByKey(
            static::$productImageDataCollection,
            ProductImageHydratorStep::KEY_FK_PRODUCT
        );

        $rowsCount = count($productImageSetNames);

        $queryParameters = [
            $rowsCount,
            $this->dataFormatter->formatStringList($productImageSetNames, $rowsCount),
            $this->dataFormatter->formatStringList($productImageSetNames, $rowsCount),
            $this->dataFormatter->formatStringList($fkProductConcreteIds, $rowsCount),
            $this->dataFormatter->formatStringList($fkProductAbstractIds, $rowsCount),
        ];

        $results = $this->propelExecutor->execute(
            $this->productImageSql->createProductImageSetSQL(),
            $queryParameters
        );

        foreach ($results as $data) {
            static::$productIdsCollection[] = [
                ProductImageHydratorStep::KEY_FK_PRODUCT_ABSTRACT => $data[ProductImageHydratorStep::KEY_FK_PRODUCT_ABSTRACT],
                ProductImageHydratorStep::KEY_FK_PRODUCT => $data[ProductImageHydratorStep::KEY_FK_PRODUCT],
            ];

            static::$productImageSetIds[][ProductImageHydratorStep::KEY_IMAGE_SET_RELATION_ID_PRODUCT_IMAGE_SET] = $data[ProductImageHydratorStep::KEY_IMAGE_SET_RELATION_ID_PRODUCT_IMAGE_SET];
        }
    }

    /**
     * @return void
     */
    protected function persistProductAbstractImages(): void
    {
        $externalUrlLargeCollection = $this->dataFormatter->getCollectionDataByKey(static::$productImageDataCollection, self::COLUMN_EXTERNAL_URL_LARGE);
        $externalUrlSmallCollection = $this->dataFormatter->getCollectionDataByKey(static::$productImageDataCollection, self::COLUMN_EXTERNAL_URL_SMALL);
        $productImageKeys = $this->dataFormatter->getCollectionDataByKey(static::$productImageDataCollection, CombinedProductImageHydratorStep::COLUMN_PRODUCT_IMAGE_KEY);

        $parameters = [
            count($externalUrlLargeCollection),
            $this->dataFormatter->formatStringList($externalUrlLargeCollection),
            $this->dataFormatter->formatStringList($externalUrlSmallCollection),
            $this->dataFormatter->formatStringList($productImageKeys),
        ];

        $results = $this->propelExecutor->execute(
            $this->productImageSql->createOrUpdateProductImageSQL(),
            $parameters
        );

        static::$productImageIds = $results;
    }

    /**
     * @return void
     */
    protected function persistProductAbstractImageSetRelations(): void
    {
        $productImageIds = $this->dataFormatter->getCollectionDataByKey(static::$productImageIds, ProductImageHydratorStep::KEY_IMAGE_SET_RELATION_ID_PRODUCT_IMAGE);
        $productImageSetIds = $this->dataFormatter->getCollectionDataByKey(static::$productImageSetIds, ProductImageHydratorStep::KEY_IMAGE_SET_RELATION_ID_PRODUCT_IMAGE_SET);
        $sortOrder = $this->dataFormatter->getCollectionDataByKey(static::$productImageDataCollection, CombinedProductImageHydratorStep::COLUMN_SORT_ORDER);

        $parameters = [
            count(static::$productImageDataCollection),
            $this->dataFormatter->formatStringList($productImageIds),
            $this->dataFormatter->formatStringList($productImageSetIds),
            $this->dataFormatter->formatStringList($sortOrder),
        ];

        $this->propelExecutor->execute(
            $this->productImageSql->createProductImageSetRelationSQL(),
            $parameters,
            false
        );
    }

    /**
     * @return void
     */
    protected function triggerEventsForUpdatedImageSets(): void
    {
        $this->addProductImageSetChangeEvent();
    }
}
