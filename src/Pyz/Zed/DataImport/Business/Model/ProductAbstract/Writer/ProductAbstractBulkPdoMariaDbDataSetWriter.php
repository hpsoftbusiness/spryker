<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\Model\ProductAbstract\Writer;

use Propel\Runtime\Propel;
use Pyz\Zed\DataImport\Business\Model\ProductAbstract\ProductAbstractHydratorStep;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface;
use Spryker\Zed\DataImport\Business\Model\Publisher\DataImporterPublisher;
use Spryker\Zed\Product\Dependency\ProductEvents;
use Spryker\Zed\ProductCategory\Dependency\ProductCategoryEvents;
use Spryker\Zed\Url\Dependency\UrlEvents;

class ProductAbstractBulkPdoMariaDbDataSetWriter extends AbstractProductAbstractBulkPdoDataSetWriter implements DataSetWriterInterface
{
//    /**
//     * @return void
//     */
//    protected function persistAbstractProductEntities(): void
//    {
//        if (!empty(static::$productAbstractCollection)) {
//            $rawAbstractSkus = $this->dataFormatter->getCollectionDataByKey(static::$productAbstractCollection, ProductAbstractHydratorStep::KEY_SKU);
//            $abstractSkus = $this->dataFormatter->formatStringList($rawAbstractSkus);
//
//            $rowsCount = count($rawAbstractSkus);
//
//            $attributes = $this->dataFormatter->formatPriceStringList(
//                $this->dataFormatter->getCollectionDataByKey(static::$productAbstractCollection, ProductAbstractHydratorStep::KEY_ATTRIBUTES),
//                $rowsCount
//            );
//
//            $fkTaxSets = $this->dataFormatter->formatStringList(
//                $this->dataFormatter->getCollectionDataByKey(static::$productAbstractCollection, ProductAbstractHydratorStep::KEY_FK_TAX_SET),
//                $rowsCount
//            );
//            $colorCode = $this->dataFormatter->formatStringList(
//                $this->dataFormatter->getCollectionDataByKey(static::$productAbstractCollection, ProductAbstractHydratorStep::COLUMN_COLOR_CODE),
//                $rowsCount
//            );
//            $isAffiliates = $this->dataFormatter->formatStringList(
//                $this->dataFormatter->getCollectionDataByKey(static::$productAbstractCollection, CombinedProductAbstractHydratorStep::COLUMN_IS_AFFILIATE)
//            );
//
//            $affiliateDatas = $this->dataFormatter->formatPriceStringList(
//                $this->dataFormatter->getCollectionDataByKey(static::$productAbstractCollection, CombinedProductAbstractHydratorStep::COLUMN_AFFILIATE_DATA),
//                $rowsCount
//            );
//
//            $sql = $this->productAbstractSql->createAbstractProductSQL();
//
//            $parameters = [
//                $rowsCount,
//                $abstractSkus,
//                $attributes,
//                $fkTaxSets,
//                $colorCode,
//                $isAffiliates,
//                $affiliateDatas,
//            ];
//
//            $result = $this->propelExecutor->execute($sql, $parameters);
//
//            foreach ($result as $columns) {
//                static::$productAbstractUpdated[] = $columns[ProductAbstractHydratorStep::KEY_ID_PRODUCT_ABSTRACT];
//            }
//        }
//    }

    /**
     * @return void
     */
    protected function persistAbstractProductEntities(): void
    {
        if (!empty(static::$productAbstractCollection)) {
            $parameter = $this->dataFormatter->collectMultiInsertData(
                static::$productAbstractCollection
            );

            $sql = 'INSERT INTO `spy_product_abstract` (`sku`, `color_code`, `fk_tax_set`, `attributes`, `is_affiliate`, `affiliate_data`, `brand`) VALUES' . $parameter . ' ON DUPLICATE KEY UPDATE sku=values(sku), color_code=values(color_code), fk_tax_set=values(fk_tax_set), attributes=values(attributes), is_affiliate=values(is_affiliate), affiliate_data=values(affiliate_data), brand=value(brand);';

            $connection = Propel::getConnection();
            $statement = $connection->prepare($sql);
            $statement->execute();

            $rawAbstractSkus = $this->dataFormatter->getCollectionDataByKey(static::$productAbstractCollection, ProductAbstractHydratorStep::KEY_SKU);
            $abstractSkus = $this->dataFormatter->formatStringList($rawAbstractSkus);
            $rowCount = count($rawAbstractSkus);
            $sortKey = $this->dataFormatter->formatStringList(
                array_keys($rawAbstractSkus),
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
                  input.abstract_sku,
                  spy_product_abstract.id_product_abstract as product_abstract_id
                FROM
                    (
                       SELECT DISTINCT
                            NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.sortKeys, \',\', n.digit + 1), \',\', -1), \'\') as sortKey,
                            NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.abstract_skus, \',\', n.digit + 1), \',\', -1), \'\') as abstract_sku
                       FROM (
                            SELECT ? as sortKeys,
                                   ? as abstract_skus
                       ) temp
                       INNER JOIN n
                          ON LENGTH(REPLACE(sortKeys, \',\', \'\')) <= LENGTH(sortKeys) - n.digit
                          AND LENGTH(REPLACE(abstract_skus, \',\', \'\')) <= LENGTH(abstract_skus) - n.digit
                    ) input
                    LEFT JOIN spy_product_abstract ON spy_product_abstract.sku = input.abstract_sku
            ) SELECT records.product_abstract_id as id_product_abstract, records.abstract_sku FROM records ORDER BY records.sortKey';

            $result = $this->propelExecutor->execute($sql, [
                $rowCount,
                $sortKey,
                $abstractSkus,
            ]);

            foreach ($result as $columns) {
                $abstractSku = $columns[ProductAbstractHydratorStep::COLUMN_ABSTRACT_SKU];
                $idProductAbstract = (int)$columns[ProductAbstractHydratorStep::KEY_ID_PRODUCT_ABSTRACT];
                static::$productAbstractUpdated[] = (int)$columns[ProductAbstractHydratorStep::KEY_ID_PRODUCT_ABSTRACT];
                foreach (static::$productAbstractLocalizedAttributesCollection[$abstractSku] as $idLocale => $productAbstractLocalizedAttributesData) {
                    static::$productAbstractLocalizedAttributesCollection[$abstractSku][$idLocale][ProductAbstractHydratorStep::COLUMN_ABSTRACT_SKU] = $idProductAbstract;
                }
                static::$productCategoryCollection[$abstractSku][ProductAbstractHydratorStep::COLUMN_ABSTRACT_SKU] = $idProductAbstract;

                foreach (static::$productUrlCollection[$abstractSku] as $idLocale => $productUrlData) {
                    static::$productUrlCollection[$abstractSku][$idLocale][ProductAbstractHydratorStep::COLUMN_ABSTRACT_SKU] = $idProductAbstract;
                    static::$productUrlListCollection[$abstractSku . $idLocale] = [
                        ProductAbstractHydratorStep::COLUMN_ABSTRACT_SKU => $idProductAbstract,
                        ProductAbstractHydratorStep::KEY_FK_LOCALE => $idLocale,
                    ];
                }
            }
        }
    }

    /**
     * @return void
     */
    protected function persistAbstractProductLocalizedAttributesEntities(): void
    {
        if (!empty(static::$productAbstractLocalizedAttributesCollection)) {
            $parameter = $this->dataFormatter->collectMultiInsertDataForLocalizedAttributes(
                static::$productAbstractLocalizedAttributesCollection
            );

            $sql = 'INSERT INTO `spy_product_abstract_localized_attributes` (`name`, `description`, `meta_title`, `meta_description`, `meta_keywords`, `fk_locale`, `attributes`, `fk_product_abstract`) VALUES' . $parameter . ' ON DUPLICATE KEY UPDATE name=values(name), description=values(description), meta_title=values(meta_title), meta_description=values(meta_description), meta_keywords=values(meta_keywords), fk_locale=values(fk_locale), attributes=values(attributes), fk_product_abstract=values(fk_product_abstract);';

            $connection = Propel::getConnection();
            $statement = $connection->prepare($sql);
            $statement->execute();
        }
    }

//    /**
//     * @return void
//     */
//    protected function persistAbstractProductLocalizedAttributesEntities(): void
//    {
//        if (!empty(static::$productAbstractLocalizedAttributesCollection)) {
//            $rawAbstractSkus = $this->dataFormatter->getCollectionDataByKey(static::$productAbstractLocalizedAttributesCollection, ProductAbstractHydratorStep::COLUMN_ABSTRACT_SKU);
//            $abstractSkus = $this->dataFormatter->formatStringList($rawAbstractSkus);
//            $rowsCount = count($rawAbstractSkus);
//            $idLocale = $this->dataFormatter->formatStringList(
//                $this->dataFormatter->getCollectionDataByKey(static::$productAbstractLocalizedAttributesCollection, ProductAbstractHydratorStep::KEY_FK_LOCALE),
//                $rowsCount
//            );
//            $name = $this->dataFormatter->formatStringList(
//                $this->dataFormatter->getCollectionDataByKey(static::$productAbstractLocalizedAttributesCollection, ProductAbstractHydratorStep::COLUMN_NAME),
//                $rowsCount
//            );
//            $description = $this->dataFormatter->formatStringList(
//                $this->dataFormatter->getCollectionDataByKey(static::$productAbstractLocalizedAttributesCollection, ProductAbstractHydratorStep::COLUMN_DESCRIPTION),
//                $rowsCount
//            );
//            $metaTitle = $this->dataFormatter->formatStringList(
//                $this->dataFormatter->getCollectionDataByKey(static::$productAbstractLocalizedAttributesCollection, ProductAbstractHydratorStep::COLUMN_META_TITLE),
//                $rowsCount
//            );
//            $metaDescription = $this->dataFormatter->formatStringList(
//                $this->dataFormatter->getCollectionDataByKey(static::$productAbstractLocalizedAttributesCollection, ProductAbstractHydratorStep::COLUMN_META_DESCRIPTION),
//                $rowsCount
//            );
//            $metaKeywords = $this->dataFormatter->formatStringList(
//                $this->dataFormatter->getCollectionDataByKey(static::$productAbstractLocalizedAttributesCollection, ProductAbstractHydratorStep::COLUMN_META_KEYWORDS),
//                $rowsCount
//            );
//
//            $attributes = $this->dataFormatter->formatPriceStringList(
//                $this->dataFormatter->getCollectionDataByKey(static::$productAbstractLocalizedAttributesCollection, ProductAbstractHydratorStep::KEY_ATTRIBUTES),
//                $rowsCount
//            );
//
//            $sql = $this->productAbstractSql->createAbstractProductLocalizedAttributesSQL();
//            $parameters = [
//                $rowsCount,
//                $abstractSkus,
//                $name,
//                $description,
//                $metaTitle,
//                $metaDescription,
//                $metaKeywords,
//                $idLocale,
//                $attributes,
//            ];
//
//            $this->propelExecutor->execute($sql, $parameters, false);
//        }
//    }

    /**
     * return void
     *
     * @return void
     */
    protected function persistAbstractProductCategoryEntities(): void
    {
        if (!empty(static::$productCategoryCollection)) {
            $parameter = $this->dataFormatter->collectMultiInsertData(
                static::$productCategoryCollection
            );

            $sql = 'INSERT INTO `spy_product_category` (`fk_category`, `product_order`, `fk_product_abstract`) VALUES' . $parameter . ' ON DUPLICATE KEY UPDATE fk_category=values(fk_category), product_order=values(product_order), fk_product_abstract=values(fk_product_abstract);';

            $connection = Propel::getConnection();
            $statement = $connection->prepare($sql);
            $statement->execute();

            foreach (static::$productCategoryCollection as $columns) {
                DataImporterPublisher::addEvent(ProductCategoryEvents::PRODUCT_CATEGORY_PUBLISH, (int)$columns[ProductAbstractHydratorStep::COLUMN_ABSTRACT_SKU]);
                DataImporterPublisher::addEvent(ProductEvents::PRODUCT_ABSTRACT_PUBLISH, (int)$columns[ProductAbstractHydratorStep::COLUMN_ABSTRACT_SKU]);
            }
        }
    }

//    /**
//     * return void
//     *
//     * @return void
//     */
//    protected function persistAbstractProductCategoryEntities(): void
//    {
//        if (!empty(static::$productCategoryCollection)) {
//            $rawAbstractSkus = $this->dataFormatter->getCollectionDataByKey(static::$productCategoryCollection, ProductAbstractHydratorStep::COLUMN_ABSTRACT_SKU);
//            $abstractSkus = $this->dataFormatter->formatStringList($rawAbstractSkus);
//            $rowsCount = count($rawAbstractSkus);
//            $productOrder = $this->dataFormatter->formatStringList(
//                $this->dataFormatter->getCollectionDataByKey(static::$productCategoryCollection, ProductAbstractHydratorStep::KEY_PRODUCT_ORDER),
//                $rowsCount
//            );
//            $idCategory = $this->dataFormatter->formatStringList(
//                $this->dataFormatter->getCollectionDataByKey(static::$productCategoryCollection, ProductAbstractHydratorStep::KEY_FK_CATEGORY),
//                $rowsCount
//            );
//
//            $sql = $this->productAbstractSql->createAbstractProductCategoriesSQL();
//            $parameters = [
//                $rowsCount,
//                $abstractSkus,
//                $productOrder,
//                $idCategory,
//            ];
//
//            $result = $this->propelExecutor->execute($sql, $parameters);
//
//            foreach ($result as $columns) {
//                DataImporterPublisher::addEvent(ProductCategoryEvents::PRODUCT_CATEGORY_PUBLISH, $columns[ProductAbstractHydratorStep::KEY_ID_PRODUCT_ABSTRACT]);
//                DataImporterPublisher::addEvent(ProductEvents::PRODUCT_ABSTRACT_PUBLISH, $columns[ProductAbstractHydratorStep::KEY_ID_PRODUCT_ABSTRACT]);
//            }
//        }
//    }

    /**
     * @return void
     */
    protected function persistAbstractProductUrlEntities(): void
    {
        if (!empty(static::$productUrlCollection)) {
            $parameter = $this->dataFormatter->collectMultiInsertDataForLocalizedAttributes(
                static::$productUrlCollection
            );

            $sql = 'INSERT INTO `spy_url` (`fk_locale`, `url`, `fk_resource_product_abstract`) VALUES' . $parameter . ' ON DUPLICATE KEY UPDATE fk_locale=values(fk_locale), url=values(url), fk_resource_product_abstract=values(fk_resource_product_abstract);';

            $connection = Propel::getConnection();
            $statement = $connection->prepare($sql);
            $statement->execute();

            $rawAbstractSkus = $this->dataFormatter->getCollectionDataByKey(static::$productUrlListCollection, ProductAbstractHydratorStep::COLUMN_ABSTRACT_SKU);
            $abstractSkus = $this->dataFormatter->formatStringList($rawAbstractSkus);
            $rowCount = count($rawAbstractSkus);
            $idLocale = $this->dataFormatter->formatPriceStringList(
                $this->dataFormatter->getCollectionDataByKey(static::$productUrlListCollection, ProductAbstractHydratorStep::KEY_FK_LOCALE),
                $rowCount
            );
            $sortKey = $this->dataFormatter->formatStringList(
                array_keys($rawAbstractSkus),
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
                  input.fkLocale,
                  input.fkResourceProductAbstract,
                  spy_url.id_url
                FROM
                    (
                       SELECT DISTINCT
                                NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.sortKeys, \',\', n.digit + 1), \',\', -1), \'\') as sortKey,
                                NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.fkLocales, \',\', n.digit + 1), \',\', -1), \'\') as fkLocale,
                                NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.fkResourceProductAbstracts, \',\', n.digit + 1), \',\', -1), \'\') as fkResourceProductAbstract
                       FROM (
                            SELECT ? as sortKeys,
                                   ? as fkLocales,
                                   ? as fkResourceProductAbstracts
                       ) temp
                       INNER JOIN n
                          ON LENGTH(REPLACE(sortKeys, \',\', \'\')) <= LENGTH(sortKeys) - n.digit
                          AND LENGTH(REPLACE(fkLocales, \',\', \'\')) <= LENGTH(fkLocales) - n.digit
                          AND LENGTH(REPLACE(fkResourceProductAbstracts, \',\', \'\')) <= LENGTH(fkResourceProductAbstracts) - n.digit
                    ) input
                    LEFT JOIN spy_url ON spy_url.fk_locale = input.fkLocale AND spy_url.fk_resource_product_abstract = input.fkResourceProductAbstract
            ) SELECT records.id_url FROM records ORDER BY records.sortKey';

            $result = $this->propelExecutor->execute($sql, [
                $rowCount,
                $sortKey,
                $idLocale,
                $abstractSkus,
            ]);

            foreach ($result as $columns) {
                DataImporterPublisher::addEvent(UrlEvents::URL_PUBLISH, (int)$columns[ProductAbstractHydratorStep::KEY_ID_URL]);
            }
        }
    }

//    /**
//     * @return void
//     */
//    protected function persistAbstractProductUrlEntities(): void
//    {
//        if (!empty(static::$productUrlCollection)) {
//            $rawAbstractSkus = $this->dataFormatter->getCollectionDataByKey(static::$productUrlCollection, ProductAbstractHydratorStep::COLUMN_ABSTRACT_SKU);
//            $abstractSkus = $this->dataFormatter->formatStringList($rawAbstractSkus);
//            $rowsCount = count($rawAbstractSkus);
//            $idLocale = $this->dataFormatter->formatPriceStringList(
//                $this->dataFormatter->getCollectionDataByKey(static::$productUrlCollection, ProductAbstractHydratorStep::KEY_FK_LOCALE),
//                $rowsCount
//            );
//            $url = $this->dataFormatter->formatStringList(
//                $this->dataFormatter->getCollectionDataByKey(static::$productUrlCollection, ProductAbstractHydratorStep::COLUMN_URL),
//                $rowsCount
//            );
//
//            $sql = $this->productAbstractSql->createAbstractProductUrlsSQL();
//            $parameters = [
//                $rowsCount,
//                $abstractSkus,
//                $idLocale,
//                $url,
//            ];
//
//            $result = $this->propelExecutor->execute($sql, $parameters);
//
//            foreach ($result as $columns) {
//                DataImporterPublisher::addEvent(UrlEvents::URL_PUBLISH, $columns[ProductAbstractHydratorStep::KEY_ID_URL]);
//            }
//        }
//    }
}
