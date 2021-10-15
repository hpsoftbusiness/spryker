<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\Model\ProductConcrete\Writer;

use Propel\Runtime\Propel;
use Pyz\Zed\DataImport\Business\Model\ProductAbstract\ProductAbstractHydratorStep;
use Pyz\Zed\DataImport\Business\Model\ProductConcrete\ProductConcreteHydratorStep;
use Spryker\Zed\DataImport\Business\Model\Publisher\DataImporterPublisher;
use Spryker\Zed\Product\Dependency\ProductEvents;

class ProductConcreteBulkPdoMariaDbDataSetWriter extends AbstractProductConcreteBulkDataSetWriter
{
//    /**
//     * @return void
//     */
//    protected function persistConcreteProductEntities(): void
//    {
//        $rawSku = $this->dataFormatter->getCollectionDataByKey(static::$productConcreteCollection, ProductConcreteHydratorStep::KEY_SKU);
//
//        $rowsCount = count($rawSku);
//        $sku = $this->dataFormatter->formatStringList($rawSku, $rowsCount);
//
//        $attributes = $this->dataFormatter->formatPriceStringList(
//            $this->dataFormatter->getCollectionDataByKey(static::$productConcreteCollection, ProductConcreteHydratorStep::KEY_ATTRIBUTES),
//            $rowsCount
//        );
//        $isActive = $this->dataFormatter->formatStringList(
//            $this->dataFormatter->getCollectionDataByKey(static::$productConcreteCollection, ProductConcreteHydratorStep::KEY_IS_ACTIVE),
//            $rowsCount
//        );
//        $skuProductAbstract = $this->dataFormatter->formatStringList(
//            $this->dataFormatter->getCollectionDataByKey(static::$productConcreteCollection, static::COLUMN_ABSTRACT_SKU),
//            $rowsCount
//        );
//
//        $sql = $this->productConcreteSql->createConcreteProductSQL();
//        $parameters = [
//            $rowsCount,
//            $sku,
//            $isActive,
//            $attributes,
//            $skuProductAbstract,
//        ];
//
//        $result = $this->propelExecutor->execute($sql, $parameters);
//        foreach ($result as $columns) {
//            DataImporterPublisher::addEvent(ProductEvents::PRODUCT_CONCRETE_PUBLISH, $columns[ProductConcreteHydratorStep::KEY_ID_PRODUCT]);
//        }
//    }

    /**
     * @return void
     */
    protected function persistConcreteProductEntities(): void
    {
        $rawAbstractSkus = $this->dataFormatter->getCollectionDataByKey(
            static::$productConcreteCollection,
            ProductAbstractHydratorStep::COLUMN_ABSTRACT_SKU
        );
        $abstractSkus = $this->dataFormatter->formatStringList($rawAbstractSkus);
        $rowCount = count($rawAbstractSkus);
        $uniqueKey = $this->dataFormatter->formatStringList(
            array_keys(static::$productConcreteCollection),
            $rowCount
        );
        if ($rowCount > 0) {
            $sql = '
            WITH RECURSIVE n(digit) AS (
                SELECT 0 as digit
                UNION ALL
                SELECT 1 + digit FROM n WHERE digit < ?
            ), records AS (
                SELECT
                  input.unique_key,
                  input.abstract_sku,
                  spy_product_abstract.id_product_abstract as product_abstract_id
                FROM
                    (
                       SELECT DISTINCT
                            NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.unique_keys, \',\', n.digit + 1), \',\', -1), \'\') as unique_key,
                            NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.abstract_skus, \',\', n.digit + 1), \',\', -1), \'\') as abstract_sku
                       FROM (
                            SELECT ? as unique_keys,
                                   ? as abstract_skus
                       ) temp
                       INNER JOIN n
                          ON LENGTH(REPLACE(unique_keys, \',\', \'\')) <= LENGTH(unique_keys) - n.digit
                          AND LENGTH(REPLACE(abstract_skus, \',\', \'\')) <= LENGTH(abstract_skus) - n.digit
                    ) input
                    LEFT JOIN spy_product_abstract ON spy_product_abstract.sku = input.abstract_sku
            ) SELECT records.product_abstract_id as id_product_abstract, records.abstract_sku, records.unique_key FROM records';

            $results = $this->propelExecutor->execute(
                $sql,
                [
                    $rowCount,
                    $uniqueKey,
                    $abstractSkus,
                ]
            );

            foreach ($results as $result) {
                static::$productConcreteCollection[$result['unique_key']][ProductConcreteHydratorStep::COLUMN_ABSTRACT_SKU] = (int)$result[ProductConcreteHydratorStep::KEY_ID_PRODUCT_ABSTRACT];
            }

            $parameter = $this->dataFormatter->collectMultiInsertData(
                static::$productConcreteCollection
            );

            $sql = 'INSERT INTO `spy_product` (`sku`, `is_active`, `attributes`, `is_quantity_splittable`, `fk_product_abstract`) VALUES' . $parameter . ' ON DUPLICATE KEY UPDATE sku=values(sku), is_active=values(is_active), attributes=values(attributes), attributes=values(attributes), is_quantity_splittable=values(is_quantity_splittable), fk_product_abstract=values(fk_product_abstract);';

            $connection = Propel::getConnection();
            $statement = $connection->prepare($sql);
            $statement->execute();

            $rawConcreteSkus = $this->dataFormatter->getCollectionDataByKey(
                static::$productConcreteCollection,
                ProductConcreteHydratorStep::KEY_SKU
            );
            $concreteSkus = $this->dataFormatter->formatStringList($rawConcreteSkus);
            $rowCount = count($rawConcreteSkus);
            $sortKey = $this->dataFormatter->formatStringList(
                array_keys($rawConcreteSkus),
                $rowCount
            );

            $sql = '
            WITH RECURSIVE n(digit) AS (
                SELECT 0 as digit
                UNION ALL
                SELECT 1 + digit FROM n WHERE digit < ?
            ), records AS (
                SELECT
                  input.sortKey,
                  input.concrete_sku,
                  spy_product.id_product as product_id
                FROM
                    (
                       SELECT DISTINCT
                            NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.sortKeys, \',\', n.digit + 1), \',\', -1), \'\') as sortKey,
                            NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.concrete_skus, \',\', n.digit + 1), \',\', -1), \'\') as concrete_sku
                       FROM (
                            SELECT ? as sortKeys,
                                   ? as concrete_skus
                       ) temp
                       INNER JOIN n
                          ON LENGTH(REPLACE(sortKeys, \',\', \'\')) <= LENGTH(sortKeys) - n.digit
                          AND LENGTH(REPLACE(concrete_skus, \',\', \'\')) <= LENGTH(concrete_skus) - n.digit
                    ) input
                    LEFT JOIN spy_product ON spy_product.sku = input.concrete_sku
            ) SELECT records.product_id as id_product, records.concrete_sku FROM records';

            $results = $this->propelExecutor->execute(
                $sql,
                [
                    $rowCount,
                    $sortKey,
                    $concreteSkus,
                ]
            );

            foreach ($results as $columns) {
                foreach (static::$productLocalizedAttributesCollection[$columns[ProductConcreteHydratorStep::COLUMN_CONCRETE_SKU]] as $idLocale => $productLocalizedAttributesData) {
                    static::$productLocalizedAttributesCollection[$columns[ProductConcreteHydratorStep::COLUMN_CONCRETE_SKU]][$idLocale][ProductConcreteHydratorStep::KEY_SKU] = (int)$columns[ProductConcreteHydratorStep::KEY_ID_PRODUCT];
                }
                foreach (static::$productSearchCollection[$columns[ProductConcreteHydratorStep::COLUMN_CONCRETE_SKU]] as $idLocale => $productSearchData) {
                    static::$productSearchCollection[$columns[ProductConcreteHydratorStep::COLUMN_CONCRETE_SKU]][$idLocale][ProductConcreteHydratorStep::KEY_SKU] = (int)$columns[ProductConcreteHydratorStep::KEY_ID_PRODUCT];
                }
                DataImporterPublisher::addEvent(
                    ProductEvents::PRODUCT_CONCRETE_PUBLISH,
                    (int)$columns[ProductConcreteHydratorStep::KEY_ID_PRODUCT]
                );
                DataImporterPublisher::addEvent(ProductEvents::ENTITY_SPY_PRODUCT_CREATE, (int)$columns[ProductConcreteHydratorStep::KEY_ID_PRODUCT]);
            }
        }
    }

    /**
     * @return void
     */
    protected function persistConcreteProductLocalizedAttributesEntities(): void
    {
        if (!empty(static::$productLocalizedAttributesCollection)) {
            $parameter = $this->dataFormatter->collectMultiInsertDataForLocalizedAttributes(
                static::$productLocalizedAttributesCollection
            );

            $sql = 'INSERT INTO `spy_product_localized_attributes` (`name`, `description`, `is_complete`, `attributes`, `fk_locale`, `fk_product`) VALUES' . $parameter . ' ON DUPLICATE KEY UPDATE name=values(name), description=values(description), is_complete=values(is_complete), attributes=values(attributes), fk_locale=values(fk_locale), fk_product=values(fk_product);';

            $connection = Propel::getConnection();
            $statement = $connection->prepare($sql);
            $statement->execute();
        }
    }

//    /**
//     * @return void
//     */
//    protected function persistConcreteProductLocalizedAttributesEntities(): void
//    {
//        if (!empty(static::$productLocalizedAttributesCollection)) {
//            $rawSku = $this->dataFormatter->getCollectionDataByKey(static::$productLocalizedAttributesCollection, ProductConcreteHydratorStep::KEY_SKU);
//
//            $sku = $this->dataFormatter->formatStringList($rawSku);
//
//            $rowsCount = count($rawSku);
//
//            $idLocale = $this->dataFormatter->formatStringList(
//                $this->dataFormatter->getCollectionDataByKey(static::$productLocalizedAttributesCollection, ProductConcreteHydratorStep::KEY_FK_LOCALE),
//                $rowsCount
//            );
//            $name = $this->dataFormatter->formatStringList(
//                $this->dataFormatter->getCollectionDataByKey(static::$productLocalizedAttributesCollection, self::COLUMN_NAME),
//                $rowsCount
//            );
//            $isComplete = $this->dataFormatter->formatStringList(
//                $this->dataFormatter->getCollectionDataByKey(static::$productLocalizedAttributesCollection, ProductConcreteHydratorStep::KEY_IS_COMPLETE),
//                $rowsCount
//            );
//            $description = $this->dataFormatter->formatStringList(
//                $this->dataFormatter->getCollectionDataByKey(static::$productLocalizedAttributesCollection, self::COLUMN_DESCRIPTION),
//                $rowsCount
//            );
//            $attributes = $this->dataFormatter->formatPriceStringList(
//                $this->dataFormatter->getCollectionDataByKey(static::$productLocalizedAttributesCollection, ProductConcreteHydratorStep::KEY_ATTRIBUTES),
//                $rowsCount
//            );
//
//            $sql = $this->productConcreteSql->createConcreteProductLocalizedAttributesSQL();
//            $parameters = [
//                $rowsCount,
//                $sku,
//                $name,
//                $description,
//                $attributes,
//                $isComplete,
//                $idLocale,
//            ];
//
//            $this->propelExecutor->execute($sql, $parameters);
//        }
//    }

    /**
     * @return void
     */
    protected function persistConcreteProductSearchEntities(): void
    {
        if (!empty(static::$productSearchCollection)) {
            $parameter = $this->dataFormatter->collectMultiInsertDataForLocalizedAttributes(
                static::$productSearchCollection
            );

            $sql = 'INSERT INTO `spy_product_search` (`fk_locale`, `is_searchable`, `fk_product`) VALUES' . $parameter . ' ON DUPLICATE KEY UPDATE fk_locale=values(fk_locale), is_searchable=values(is_searchable), fk_product=values(fk_product);';

            $connection = Propel::getConnection();
            $statement = $connection->prepare($sql);
            $statement->execute();
        }
    }

//    /**
//     * @return void
//     */
//    protected function persistConcreteProductSearchEntities(): void
//    {
//        if (!empty(static::$productSearchCollection)) {
//            $rawIdLocale = $this->dataFormatter->getCollectionDataByKey(static::$productSearchCollection, ProductConcreteHydratorStep::KEY_FK_LOCALE);
//
//            $idLocale = $this->dataFormatter->formatStringList($rawIdLocale);
//
//            $rowsCount = count($rawIdLocale);
//
//            $isSearchable = $this->dataFormatter->formatStringList(
//                $this->dataFormatter->getCollectionDataByKey(static::$productSearchCollection, self::COLUMN_IS_SEARCHABLE),
//                $rowsCount
//            );
//            $sku = $this->dataFormatter->formatStringList(
//                $this->dataFormatter->getCollectionDataByKey(static::$productSearchCollection, ProductConcreteHydratorStep::KEY_SKU),
//                $rowsCount
//            );
//
//            $sql = $this->productConcreteSql->createConcreteProductSearchSQL();
//            $parameters = [
//                $rowsCount,
//                $idLocale,
//                $sku,
//                $isSearchable,
//            ];
//
//            $this->propelExecutor->execute($sql, $parameters);
//        }
//    }

    /**
     * @return void
     */
    protected function persistConcreteProductBundleEntities(): void
    {
        if (!empty(static::$productBundleCollection)) {
            $rawBundledProductSku = $this->dataFormatter->getCollectionDataByKey(static::$productBundleCollection, ProductConcreteHydratorStep::KEY_PRODUCT_BUNDLE_SKU);

            $bundledProductSku = $this->dataFormatter->formatStringList($rawBundledProductSku);

            $rowsCount = count($rawBundledProductSku);

            $sku = $this->dataFormatter->formatStringList(
                $this->dataFormatter->getCollectionDataByKey(static::$productBundleCollection, ProductConcreteHydratorStep::KEY_SKU),
                $rowsCount
            );
            $quantity = $this->dataFormatter->formatStringList(
                $this->dataFormatter->getCollectionDataByKey(static::$productBundleCollection, ProductConcreteHydratorStep::KEY_QUANTITY),
                $rowsCount
            );

            $sql = $this->productConcreteSql->createConcreteProductBundleSQL();
            $parameters = [
                $rowsCount,
                $bundledProductSku,
                $sku,
                $quantity,
            ];
            $this->propelExecutor->execute($sql, $parameters);
        }
    }
}
