<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\CombinedProduct\ProductListProductConcrete\Writer;

use Pyz\Zed\DataImport\Business\CombinedProduct\ProductListProductConcrete\Writer\Sql\ProductListProductConcreteSqlInterface;
use Pyz\Zed\DataImport\Business\Model\DataFormatter\DataImportDataFormatterInterface;
use Pyz\Zed\DataImport\Business\Model\PropelExecutorInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface;
use Spryker\Zed\DataImport\Business\Model\Publisher\DataImporterPublisher;
use Spryker\Zed\ProductList\Dependency\ProductListEvents;

class CombinedProductListProductConcreteBulkPdoMariaDbDataSetWriter implements DataSetWriterInterface
{
    protected const BULK_SIZE = 2500;

    public const COLUMN_CONCRETE_SKU = 'concrete_sku';
    public const KEY_ID_PRODUCT_CONCRETE = 'id_product_concrete';
    public const KEY_PRODUCT_LIST = 'product_list_key';

    protected const KEY_ATTRIBUTES = 'attributes';

    /**
     * @var \Pyz\Zed\DataImport\Business\CombinedProduct\ProductListProductConcrete\Writer\Sql\ProductListProductConcreteSqlInterface
     */
    protected $productListProductConcreteSql;

    /**
     * @var \Pyz\Zed\DataImport\Business\Model\PropelExecutorInterface
     */
    protected $propelExecutor;

    /**
     * @var \Pyz\Zed\DataImport\Business\Model\DataFormatter\DataImportDataFormatterInterface
     */
    protected $dataFormatter;

    /**
     * @var array
     */
    protected static $productListProductConcreteDataCollection = [];

    /**
     * @param \Pyz\Zed\DataImport\Business\CombinedProduct\ProductListProductConcrete\Writer\Sql\ProductListProductConcreteSqlInterface $productListProductConcreteSql
     * @param \Pyz\Zed\DataImport\Business\Model\PropelExecutorInterface $propelExecutor
     * @param \Pyz\Zed\DataImport\Business\Model\DataFormatter\DataImportDataFormatterInterface $dataFormatter
     */
    public function __construct(
        ProductListProductConcreteSqlInterface $productListProductConcreteSql,
        PropelExecutorInterface $propelExecutor,
        DataImportDataFormatterInterface $dataFormatter
    ) {
        $this->productListProductConcreteSql = $productListProductConcreteSql;
        $this->propelExecutor = $propelExecutor;
        $this->dataFormatter = $dataFormatter;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function write(DataSetInterface $dataSet): void
    {
        $this->collectProductListProductConcreteData($dataSet);

        if (count(static::$productListProductConcreteDataCollection) >= static::BULK_SIZE) {
            $this->flush();
        }
    }

    /**
     * @return void
     */
    public function flush()
    {
        $this->persistProductListProductConcrete();

        static::$productListProductConcreteDataCollection = [];
    }

    /**
     * @return void
     */
    protected function persistProductListProductConcrete()
    {
        $concreteSkus = $this->dataFormatter->formatStringList(
            $this->dataFormatter->getCollectionDataByKey(static::$productListProductConcreteDataCollection, static::COLUMN_CONCRETE_SKU)
        );
        $productListKeys = $this->dataFormatter->formatStringList(
            $this->dataFormatter->getCollectionDataByKey(static::$productListProductConcreteDataCollection, static::KEY_PRODUCT_LIST)
        );

        $sql = $this->productListProductConcreteSql->createProductListProductConcreteSQL();

        $parameters = [
            count(static::$productListProductConcreteDataCollection),
            $concreteSkus,
            $productListKeys,
        ];

        $results = $this->propelExecutor->execute($sql, $parameters);

        foreach ($results as $result) {
            DataImporterPublisher::addEvent(ProductListEvents::PRODUCT_LIST_PRODUCT_CONCRETE_PUBLISH, $result[static::KEY_ID_PRODUCT_CONCRETE]);
        }
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    protected function collectProductListProductConcreteData(DataSetInterface $dataSet): void
    {
        foreach ($dataSet[static::KEY_ATTRIBUTES] as $productListKey => $attributeValue) {
            if ($attributeValue) {
                static::$productListProductConcreteDataCollection[] = [
                    static::COLUMN_CONCRETE_SKU => $dataSet[static::COLUMN_CONCRETE_SKU],
                    static::KEY_PRODUCT_LIST => $productListKey,
                ];
            }
        }
    }
}
