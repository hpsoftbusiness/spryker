<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\Model\ProductGroup;

use Pyz\Zed\DataImport\Business\CombinedProduct\ProductGroup\CombinedProductGroupHydratorStep;
use Pyz\Zed\DataImport\Business\Model\DataFormatter\DataImportDataFormatterInterface;
use Pyz\Zed\DataImport\Business\Model\ProductGroup\Sql\ProductGroupSqlInterface;
use Pyz\Zed\DataImport\Business\Model\PropelExecutorInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface;
use Spryker\Zed\DataImport\Business\Model\Publisher\DataImporterPublisher;
use Spryker\Zed\ProductGroup\Dependency\ProductGroupEvents;

class ProductGroupBulkPdoMariaDbDataSetWriter implements DataSetWriterInterface
{
    public const BULK_SIZE = 100;

    public const COLUMN_ABSTRACT_SKU = CombinedProductGroupHydratorStep::COLUMN_ABSTRACT_SKU;
    public const COLUMN_PRODUCT_GROUP_KEY = CombinedProductGroupHydratorStep::COLUMN_PRODUCT_GROUP_KEY;
    public const COLUMN_POSITION = CombinedProductGroupHydratorStep::COLUMN_POSITION;
    protected const FK_PRODUCT_ABSTRACT = 'fk_product_abstract';

    /**
     * @var array
     */
    protected static $productGroupKeysCollection = [];

    /**
     * @var array
     */
    protected static $productGroupCollection = [];

    /**
     * @var \Pyz\Zed\DataImport\Business\Model\PropelExecutorInterface
     */
    protected $propelExecutor;

    /**
     * @var \Pyz\Zed\DataImport\Business\Model\DataFormatter\DataImportDataFormatterInterface
     */
    protected $dataFormatter;

    /**
     * @var \Pyz\Zed\DataImport\Business\Model\ProductGroup\Sql\ProductGroupSqlInterface
     */
    protected $productGroupSql;

    /**
     * @param \Pyz\Zed\DataImport\Business\Model\PropelExecutorInterface $propelExecutor
     * @param \Pyz\Zed\DataImport\Business\Model\DataFormatter\DataImportDataFormatterInterface $dataFormatter
     * @param \Pyz\Zed\DataImport\Business\Model\ProductGroup\Sql\ProductGroupSqlInterface $productGroupSql
     */
    public function __construct(
        PropelExecutorInterface $propelExecutor,
        DataImportDataFormatterInterface $dataFormatter,
        ProductGroupSqlInterface $productGroupSql
    ) {
        $this->propelExecutor = $propelExecutor;
        $this->dataFormatter = $dataFormatter;
        $this->productGroupSql = $productGroupSql;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function write(DataSetInterface $dataSet)
    {
        $this->collectProductGroupKeysCollection($dataSet);
        $this->collectProductGroupCollection($dataSet);

        if (count(static::$productGroupCollection) >= static::BULK_SIZE) {
            $this->flush();
        }
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    protected function collectProductGroupKeysCollection(DataSetInterface $dataSet): void
    {
        static::$productGroupKeysCollection[][static::COLUMN_PRODUCT_GROUP_KEY] = $dataSet[static::COLUMN_PRODUCT_GROUP_KEY];
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    protected function collectProductGroupCollection(DataSetInterface $dataSet): void
    {
        static::$productGroupCollection[] = [
            static::COLUMN_PRODUCT_GROUP_KEY => $dataSet[static::COLUMN_PRODUCT_GROUP_KEY],
            static::COLUMN_ABSTRACT_SKU => $dataSet[static::COLUMN_ABSTRACT_SKU],
            static::COLUMN_POSITION => $dataSet[static::COLUMN_POSITION],
        ];
    }

    /**
     * @return void
     */
    public function flush()
    {
        $this->persistProductGroupEntities();
        $this->persistProductAbstractGroupEntities();

        DataImporterPublisher::triggerEvents();
        $this->flushMemory();
    }

    /**
     * @return void
     */
    protected function persistProductGroupEntities(): void
    {
        $groupNames = $this->dataFormatter->getCollectionDataByKey(static::$productGroupKeysCollection, CombinedProductGroupHydratorStep::COLUMN_PRODUCT_GROUP_KEY);
        $uniqueGroupNames = array_unique($groupNames);
        $groupNames = $this->dataFormatter->formatStringList($uniqueGroupNames);

        $sql = $this->productGroupSql->createProductGroupSQL();

        $this->propelExecutor->execute(
            $sql,
            [
                count($uniqueGroupNames),
                $groupNames,
            ],
            false
        );
    }

    /**
     * @return void
     */
    protected function persistProductAbstractGroupEntities(): void
    {
        $abstractSkus = $this->dataFormatter->formatStringList(
            $this->dataFormatter->getCollectionDataByKey(static::$productGroupCollection, CombinedProductGroupHydratorStep::COLUMN_ABSTRACT_SKU)
        );
        $groupKeys = $this->dataFormatter->formatStringList(
            $this->dataFormatter->getCollectionDataByKey(static::$productGroupCollection, CombinedProductGroupHydratorStep::COLUMN_PRODUCT_GROUP_KEY)
        );
        $positions = $this->dataFormatter->formatStringList(
            $this->dataFormatter->getCollectionDataByKey(static::$productGroupCollection, CombinedProductGroupHydratorStep::COLUMN_POSITION)
        );

        $sql = $this->productGroupSql->createProductAbstractGroupSQL();
        $parameters = [
            count(static::$productGroupCollection),
            $abstractSkus,
            $groupKeys,
            $positions,
        ];

        $results = $this->propelExecutor->execute($sql, $parameters);

        foreach ($results as $result) {
            DataImporterPublisher::addEvent(ProductGroupEvents::PRODUCT_GROUP_PUBLISH, $result[static::FK_PRODUCT_ABSTRACT]);
        }
    }

    /**
     * @return void
     */
    protected function flushMemory(): void
    {
        static::$productGroupKeysCollection = [];
        static::$productGroupCollection = [];
    }
}
