<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\Model\ProductImage\Writer;

use Pyz\Zed\DataImport\Business\CombinedProduct\ProductImage\CombinedProductImageHydratorStep;
use Pyz\Zed\DataImport\Business\Model\DataFormatter\DataImportDataFormatterInterface;
use Pyz\Zed\DataImport\Business\Model\ProductImage\ProductImageHydratorStep;
use Pyz\Zed\DataImport\Business\Model\ProductImage\Writer\Sql\ProductImageSqlInterface;
use Pyz\Zed\DataImport\Business\Model\PropelExecutorInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface;
use Spryker\Zed\DataImport\Business\Model\Publisher\DataImporterPublisher;
use Spryker\Zed\DataImport\DataImportConfig;
use Spryker\Zed\Product\Dependency\ProductEvents;
use Spryker\Zed\ProductImage\Dependency\ProductImageEvents;

abstract class AbstractProductImageBulkDataSetWriter implements DataSetWriterInterface
{
    protected const COLUMN_EXTERNAL_URL_LARGE = ProductImageHydratorStep::COLUMN_EXTERNAL_URL_LARGE;
    protected const COLUMN_EXTERNAL_URL_SMALL = ProductImageHydratorStep::COLUMN_EXTERNAL_URL_SMALL;
    protected const COLUMN_PRODUCT_IMAGE_KEY = ProductImageHydratorStep::COLUMN_PRODUCT_IMAGE_KEY;
    protected const COLUMN_SORT_ORDER = ProductImageHydratorStep::COLUMN_SORT_ORDER;

    /**
     * @var \Pyz\Zed\DataImport\Business\Model\ProductImage\Writer\Sql\ProductImageSqlInterface
     */
    protected $productImageSql;

    /**
     * @var \Pyz\Zed\DataImport\Business\Model\PropelExecutorInterface
     */
    protected $propelExecutor;

    /**
     * @var \Pyz\Zed\DataImport\Business\Model\DataFormatter\DataImportDataFormatterInterface
     */
    protected $dataFormatter;

    /**
     * @var \Spryker\Zed\DataImport\DataImportConfig
     */
    protected $dataImportConfig;

    /**
     * @var array
     */
    protected static $productImageDataCollection = [];

    /**
     * @var array
     */
    protected static $productIdsCollection = [];

    /**
     * @var array
     */
    protected static $productImageIds = [];

    /**
     * @var array
     */
    protected static $productImageSetIds = [];

    /**
     * @param \Pyz\Zed\DataImport\Business\Model\ProductImage\Writer\Sql\ProductImageSqlInterface $productImageSql
     * @param \Pyz\Zed\DataImport\Business\Model\PropelExecutorInterface $propelExecutor
     * @param \Pyz\Zed\DataImport\Business\Model\DataFormatter\DataImportDataFormatterInterface $dataFormatter
     * @param \Spryker\Zed\DataImport\DataImportConfig $dataImportConfig
     */
    public function __construct(
        ProductImageSqlInterface $productImageSql,
        PropelExecutorInterface $propelExecutor,
        DataImportDataFormatterInterface $dataFormatter,
        DataImportConfig $dataImportConfig
    ) {
        $this->productImageSql = $productImageSql;
        $this->propelExecutor = $propelExecutor;
        $this->dataFormatter = $dataFormatter;
        $this->dataImportConfig = $dataImportConfig;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function write(DataSetInterface $dataSet): void
    {
        $this->collectProductImageData($dataSet);

        if (count(static::$productImageDataCollection) >= ProductImageHydratorStep::BULK_SIZE) {
            $this->flush();
        }
    }

    /**
     * @return void
     */
    public function flush(): void
    {
        if (static::$productImageDataCollection !== []) {
            $this->persistProductAbstractImageSet();
            $this->persistProductAbstractImages();
            $this->persistProductAbstractImageSetRelations();
            $this->triggerEventsForUpdatedImageSets();
            static::$productImageDataCollection = [];
            static::$productIdsCollection = [];
            static::$productImageSetIds = [];
            static::$productImageIds = [];
            DataImporterPublisher::triggerEvents();
        }
    }

    /**
     * @return void
     */
    abstract protected function persistProductAbstractImageSet(): void;

    /**
     * @return void
     */
    abstract protected function persistProductAbstractImages(): void;

    /**
     * @return void
     */
    abstract protected function persistProductAbstractImageSetRelations(): void;

    /**
     * @return void
     */
    abstract protected function triggerEventsForUpdatedImageSets(): void;

    /**
     * @return void
     */
    protected function addProductImageSetChangeEvent(): void
    {
        foreach (static::$productIdsCollection as $productImageSet) {
            if (!empty($productImageSet[ProductImageHydratorStep::KEY_IMAGE_SET_FK_PRODUCT_ABSTRACT])) {
                DataImporterPublisher::addEvent(
                    ProductImageEvents::PRODUCT_IMAGE_PRODUCT_ABSTRACT_PUBLISH,
                    $productImageSet[ProductImageHydratorStep::KEY_IMAGE_SET_FK_PRODUCT_ABSTRACT]
                );
                DataImporterPublisher::addEvent(
                    ProductEvents::PRODUCT_ABSTRACT_PUBLISH,
                    $productImageSet[ProductImageHydratorStep::KEY_IMAGE_SET_FK_PRODUCT_ABSTRACT]
                );
            }
            if (!empty($productImageSet[ProductImageHydratorStep::KEY_IMAGE_SET_FK_PRODUCT])) {
                DataImporterPublisher::addEvent(
                    ProductImageEvents::PRODUCT_IMAGE_PRODUCT_CONCRETE_PUBLISH,
                    $productImageSet[ProductImageHydratorStep::KEY_IMAGE_SET_FK_PRODUCT]
                );
            }
        }
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    protected function collectProductImageData(DataSetInterface $dataSet): void
    {
        $productImageData = $dataSet[ProductImageHydratorStep::DATA_PRODUCT_IMAGE_SET_TRANSFER]->modifiedToArray();
        $productImageData = array_merge($productImageData, $dataSet[ProductImageHydratorStep::DATA_PRODUCT_IMAGE_TRANSFER]->modifiedToArray());

        $productImageData[CombinedProductImageHydratorStep::COLUMN_CONCRETE_SKU] = $dataSet[CombinedProductImageHydratorStep::COLUMN_CONCRETE_SKU];
        $productImageData[CombinedProductImageHydratorStep::COLUMN_ABSTRACT_SKU] = $dataSet[CombinedProductImageHydratorStep::COLUMN_ABSTRACT_SKU];
        $productImageData[CombinedProductImageHydratorStep::COLUMN_SORT_ORDER] = CombinedProductImageHydratorStep::IMAGE_TO_IMAGE_SET_RELATION_ORDER;

        $imageKey = $productImageData[CombinedProductImageHydratorStep::COLUMN_ABSTRACT_SKU] . '_' . $productImageData[CombinedProductImageHydratorStep::COLUMN_CONCRETE_SKU];
        $productImageData[CombinedProductImageHydratorStep::COLUMN_PRODUCT_IMAGE_KEY] = $imageKey;
        $productImageData[CombinedProductImageHydratorStep::COLUMN_PRODUCT_IMAGE_SET_KEY] = $imageKey;
        $productImageData[CombinedProductImageHydratorStep::KEY_IMAGE_SET_FK_LOCALE] = null;

        if (empty($productImageData[CombinedProductImageHydratorStep::KEY_FK_PRODUCT])) {
            $productImageData[CombinedProductImageHydratorStep::KEY_FK_PRODUCT] = null;
        }

        static::$productImageDataCollection[] = $productImageData;
    }
}
