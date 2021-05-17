<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\Model\ProductPrice\Writer;

use Pyz\Zed\DataImport\Business\Model\ProductPrice\ProductPriceHydratorStep;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface;

class ProductPriceBulkPdoMariaDbDataSetWriter extends AbstractProductPriceBulkDataSetWriter implements DataSetWriterInterface
{
    /**
     * @param array $priceProductCollection
     *
     * @return void
     */
    protected function persistProductPriceTypeCollection(array $priceProductCollection): void
    {
        $priceTypeCollection = $this->dataFormatter->getCollectionDataByKey($priceProductCollection, ProductPriceHydratorStep::KEY_PRICE_TYPE_NAME);
        $priceTypeModeCollection = $this->dataFormatter->getCollectionDataByKey($priceProductCollection, ProductPriceHydratorStep::KEY_PRICE_MODE_CONFIGURATION);

        $rowCount = count($priceTypeCollection);
        $priceType = $this->dataFormatter->formatStringList($priceTypeCollection, $rowCount);
        $priceMode = $this->dataFormatter->formatStringList($priceTypeModeCollection, $rowCount);

        $sql = $this->productPriceSql->createPriceTypeSQL();
        $parameters = [
            $rowCount,
            $priceType,
            $priceMode,
        ];

        $this->propelExecutor->execute($sql, $parameters, false);
    }

    /**
     * @param array $priceProductConcreteCollection
     *
     * @return void
     */
    protected function prepareProductPriceTypeIdsCollection(array $priceProductConcreteCollection): void
    {
        $priceTypeCollection = $this->dataFormatter->getCollectionDataByKey($priceProductConcreteCollection, ProductPriceHydratorStep::KEY_PRICE_TYPE_NAME);

        $rowCount = count($priceTypeCollection);
        $priceType = $this->dataFormatter->formatStringList($priceTypeCollection, $rowCount);

        $sql = $this->productPriceSql->collectPriceTypes();

        $parameters = [
            $rowCount,
            $priceType,
        ];

        $priceTypeIds = $this->propelExecutor->execute($sql, $parameters);

        foreach ($priceTypeIds as $idPriceType) {
            static::$productPriceTypeIdsCollection[] = $idPriceType;
        }
    }

    /**
     * @param array $priceProductCollection
     *
     * @return void
     */
    protected function prepareProductStoreIdsCollection(array $priceProductCollection): void
    {
        $storeCollection = $this->dataFormatter->getCollectionDataByKey($priceProductCollection, self::COLUMN_STORE);
        $storeNameCollection = $this->dataFormatter->getCollectionDataByKey($storeCollection, ProductPriceHydratorStep::KEY_STORE_NAME);

        $rowCount = count($storeNameCollection);
        $store = $this->dataFormatter->formatStringList($storeNameCollection, $rowCount);

        $sql = $this->productPriceSql->convertStoreNameToId();

        $parameters = [
            $rowCount,
            $store,
        ];

        $result = $this->propelExecutor->execute($sql, $parameters);

        foreach ($result as $idStore) {
            static::$productStoreIdsCollection[] = $idStore;
        }
    }

    /**
     * @param array $priceProductCollection
     *
     * @return void
     */
    protected function prepareProductCurrencyIdsCollection(array $priceProductCollection): void
    {
        $currencyCollection = $this->dataFormatter->getCollectionDataByKey($priceProductCollection, self::COLUMN_CURRENCY);
        $currencyNameCollection = $this->dataFormatter->getCollectionDataByKey($currencyCollection, ProductPriceHydratorStep::KEY_CURRENCY_NAME);

        $rowCount = count($currencyNameCollection);
        $currency = $this->dataFormatter->formatStringList($currencyNameCollection, $rowCount);

        $sql = $this->productPriceSql->convertCurrencyNameToId();

        $parameters = [
            $rowCount,
            $currency,
        ];

        $result = $this->propelExecutor->execute($sql, $parameters);

        foreach ($result as $idCurrency) {
            static::$productCurrencyIdsCollection[] = $idCurrency;
        }
    }

    /**
     * @param array $priceProductCollection
     * @param string $tableName
     * @param string $productKey
     *
     * @return void
     */
    protected function prepareProductIdsCollection(
        array $priceProductCollection,
        string $tableName,
        string $productKey
    ): void {
        $productConcreteSkuCollection = $this->dataFormatter->getCollectionDataByKey($priceProductCollection, ProductPriceHydratorStep::KEY_SKU);

        $rowCount = count($productConcreteSkuCollection);
        $productSku = $this->dataFormatter->formatStringList($productConcreteSkuCollection, $rowCount);

        $sql = $this->productPriceSql->convertProductSkuToId($tableName, $productKey);

        $parameters = [
            $rowCount,
            $productSku,
        ];

        $result = $this->propelExecutor->execute($sql, $parameters);

        foreach ($result as $idProduct) {
            static::$productIds[] = $idProduct;
        }
    }

    /**
     * @param string $productIdKey
     * @param string $productTable
     * @param string $productFkKey
     *
     * @return void
     */
    protected function persistPriceProductEntities(
        string $productIdKey,
        string $productTable,
        string $productFkKey
    ): void {
        if (!static::$productIds) {
            return;
        }

        $productCollection = $this->dataFormatter->getCollectionDataByKey(static::$productIds, $productIdKey);

        $rowCount = count($productCollection);
        $product = $this->dataFormatter->formatStringList($productCollection, $rowCount);
        $priceType = $this->dataFormatter->formatStringList(
            $this->dataFormatter->getCollectionDataByKey(static::$productPriceTypeIdsCollection, ProductPriceHydratorStep::KEY_ID_PRICE_TYPE),
            $rowCount
        );

        $priceProductAbstractParameters = [
            $rowCount,
            $product,
            $priceType,
        ];

        $sql = $this->productPriceSql->createPriceProductSQL(
            $productIdKey,
            $productTable,
            $productFkKey
        );

        $this->propelExecutor->execute($sql, $priceProductAbstractParameters, false);
    }

    /**
     * @param string $productIdKey
     * @param string $productFkKey
     *
     * @return array
     */
    protected function addPriceProductEvents(string $productIdKey, string $productFkKey): array
    {
        $productCollection = $this->dataFormatter->getCollectionDataByKey(static::$productIds, $productIdKey);

        $rowCount = count($productCollection);
        $product = $this->dataFormatter->formatStringList($productCollection, $rowCount);
        $priceType = $this->dataFormatter->formatStringList(
            $this->dataFormatter->getCollectionDataByKey(static::$productPriceTypeIdsCollection, ProductPriceHydratorStep::KEY_ID_PRICE_TYPE),
            $rowCount
        );

        $selectProductPriceSql = $this->productPriceSql->selectProductPriceSQL(
            $productIdKey,
            $productFkKey
        );

        $priceProductAbstractProductParameters = [
            $rowCount,
            $product,
            $priceType,
        ];

        $result = $this->propelExecutor->execute($selectProductPriceSql, $priceProductAbstractProductParameters);

        return $result;
    }

    /**
     * @param array $priceProductCollection
     * @param string $productTableName
     * @param string $productIdKey
     * @param string $productFkKey
     *
     * @return void
     */
    protected function persistPriceProductStoreEntities(
        array $priceProductCollection,
        string $productTableName,
        string $productIdKey,
        string $productFkKey
    ): void {
        $productCollection = $this->dataFormatter->getCollectionDataByKey(static::$priceProductIds, $productIdKey);

        $rowCount = count($priceProductCollection);
        $product = $this->dataFormatter->formatStringList($productCollection, $rowCount);
        $currency = $this->dataFormatter->formatStringList(
            $this->dataFormatter->getCollectionDataByKey(static::$productCurrencyIdsCollection, ProductPriceHydratorStep::KEY_ID_CURRENCY),
            $rowCount
        );

        $store = $this->dataFormatter->formatStringList(
            $this->dataFormatter->getCollectionDataByKey(static::$productStoreIdsCollection, ProductPriceHydratorStep::KEY_ID_STORE),
            $rowCount
        );

        $productPrice = $this->dataFormatter->formatStringList(
            $this->dataFormatter->getCollectionDataByKey(static::$priceProductIds, ProductPriceHydratorStep::KEY_ID_PRICE_PRODUCT),
            $rowCount
        );

        $grossPrice = $this->dataFormatter->formatStringList(
            $this->dataFormatter->getCollectionDataByKey($priceProductCollection, ProductPriceHydratorStep::KEY_PRICE_GROSS_DB),
            $rowCount
        );
        $netPrice = $this->dataFormatter->formatStringList(
            $this->dataFormatter->getCollectionDataByKey($priceProductCollection, ProductPriceHydratorStep::KEY_PRICE_NET_DB),
            $rowCount
        );
        $priceData = $this->dataFormatter->formatPriceStringList(
            $this->dataFormatter->getCollectionDataByKey($priceProductCollection, static::COLUMN_PRICE_DATA),
            $rowCount
        );
        $checksum = $this->dataFormatter->formatStringList(
            $this->dataFormatter->getCollectionDataByKey($priceProductCollection, static::COLUMN_PRICE_DATA_CHECKSUM),
            $rowCount
        );

        $priceProductConcreteStoreParameters = [
            $rowCount,
            $store,
            $currency,
            $product,
            $productPrice,
            $grossPrice,
            $netPrice,
            $priceData,
            $checksum,
            $productPrice,
        ];

        $sql = $this->productPriceSql->createPriceProductStoreSql(
            $productTableName,
            $productFkKey,
            $productIdKey
        );

        $priceProductStoreIds = $this->propelExecutor->execute($sql, $priceProductConcreteStoreParameters);

        foreach ($priceProductStoreIds as $idPriceProductStore) {
            static::$priceProductStoreIds[] = $idPriceProductStore;
        }
    }

    /**
     * @return void
     */
    protected function persistPriceProductDefault(): void
    {
        $priceProductStoreCollection = $this->dataFormatter->getCollectionDataByKey(
            static::$priceProductStoreIds,
            ProductPriceHydratorStep::KEY_ID_PRICE_PRODUCT_STORE
        );

        $rowCount = count($priceProductStoreCollection);
        $priceProductStoreIds = $this->dataFormatter->formatStringList($priceProductStoreCollection, $rowCount);

        $parameters = [
            $rowCount,
            $priceProductStoreIds,
        ];

        $sql = $this->productPriceSql->createPriceProductDefaultSql();

        $this->propelExecutor->execute($sql, $parameters, false);
    }
}
