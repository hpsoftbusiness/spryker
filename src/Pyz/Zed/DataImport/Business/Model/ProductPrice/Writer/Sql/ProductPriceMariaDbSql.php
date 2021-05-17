<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\Model\ProductPrice\Writer\Sql;

class ProductPriceMariaDbSql implements ProductPriceSqlInterface
{
    /**
     * @param string $idProduct
     * @param string $productTable
     * @param string $productFkKey
     *
     * @return string
     */
    public function createPriceProductSQL(string $idProduct, string $productTable, string $productFkKey): string
    {
        $sql = "
            INSERT INTO spy_price_product (
                      id_price_product,
                      fk_price_type,
                      %3\$s
            )
            WITH RECURSIVE n(digit) AS (
                SELECT 0 as digit
                UNION ALL
                SELECT 1 + digit FROM n WHERE digit < ?
            ), records AS (
                SELECT
                  input.%1\$s,
                  input.id_price_type,
                  spy_price_product.id_price_product as idPriceProduct
                FROM (
                        SELECT
                            NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.%1\$ss, ',', n.digit + 1), ',', -1), '') as %1\$s,
                            NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.id_price_types, ',', n.digit + 1), ',', -1), '') as id_price_type
                        FROM (
                            SELECT ? as %1\$ss,
                                   ? as id_price_types
                        ) temp
                        INNER JOIN n
                          ON LENGTH(REPLACE(%1\$ss, ',', '')) <= LENGTH(%1\$ss) - n.digit
                              AND LENGTH(REPLACE(id_price_types, ',', '')) <= LENGTH(id_price_types) - n.digit
                    ) input
                LEFT JOIN spy_price_product ON (spy_price_product.fk_price_type = input.id_price_type AND spy_price_product.%3\$s = input.%1\$s)
            )
            (
              SELECT
                idPriceProduct,
                id_price_type,
                %1\$s
                FROM records
            )
            ON DUPLICATE KEY UPDATE %3\$s = records.%1\$s,
                fk_price_type = records.id_price_type";

        return sprintf($sql, $idProduct, $productTable, $productFkKey);
    }

    /**
     * @param string $idProduct
     * @param string $productFkKey
     *
     * @return string
     */
    public function selectProductPriceSQL(string $idProduct, string $productFkKey): string
    {
        $sql = "
            WITH RECURSIVE n(digit) AS (
                SELECT 0 as digit
                UNION ALL
                SELECT 1 + digit FROM n WHERE digit < ?
            ), records AS (
                SELECT
                  input.%1\$s,
                  input.id_price_type,
                  spy_price_product.id_price_product
                        FROM (
                            SELECT
                                NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.%1\$ss, ',', n.digit + 1), ',', -1), '') as %1\$s,
                                NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.id_price_types, ',', n.digit + 1), ',', -1), '') as id_price_type
                            FROM (
                                SELECT ? as %1\$ss,
                                       ? as id_price_types
                            ) temp
                            INNER JOIN n
                              ON LENGTH(REPLACE(%1\$ss, ',', '')) <= LENGTH(%1\$ss) - n.digit
                                  AND LENGTH(REPLACE(id_price_types, ',', '')) <= LENGTH(id_price_types) - n.digit
                        ) input
                        LEFT JOIN spy_price_product ON (spy_price_product.fk_price_type = input.id_price_type AND spy_price_product.%2\$s = input.%1\$s)
            ) SELECT records.%1\$s, records.id_price_product FROM records";

        return sprintf($sql, $idProduct, $productFkKey);
    }

    /**
     * @return string
     */
    public function createPriceTypeSQL(): string
    {
        return '
            INSERT INTO spy_price_type (
              id_price_type,
              name,
              price_mode_configuration
            )
            WITH RECURSIVE n(digit) AS (
                SELECT 0 as digit
                UNION ALL
                SELECT 1 + digit FROM n WHERE digit < ?
            ), records AS (
                SELECT
                  input.name,
                  input.price_mode_configuration,
                  spy_price_type.id_price_type as price_type_id
                FROM (
                    SELECT
                        NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.names, \',\', n.digit + 1), \',\', -1), \'\') as name,
                        NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.price_mode_configurations, \',\', n.digit + 1), \',\', -1), \'\') as price_mode_configuration
                    FROM (
                        SELECT ? as names,
                               ? as price_mode_configurations
                    ) temp
                    INNER JOIN n
                      ON LENGTH(REPLACE(names, \',\', \'\')) <= LENGTH(names) - n.digit
                          AND LENGTH(REPLACE(price_mode_configurations, \',\', \'\')) <= LENGTH(price_mode_configurations) - n.digit
                ) input
                LEFT JOIN spy_price_type ON spy_price_type.name = input.name
            )
            (
                SELECT
                    price_type_id,
                    name,
                    price_mode_configuration
                FROM records
            )
            ON DUPLICATE KEY UPDATE
                price_mode_configuration = records.price_mode_configuration';
    }

    /**
     * @param string $tableName
     * @param string $foreignKey
     * @param string $idProduct
     *
     * @return string
     */
    public function createPriceProductStoreSql(string $tableName, string $foreignKey, string $idProduct): string
    {
        $sql = "
            INSERT INTO spy_price_product_store (
              id_price_product_store,
              fk_store,
              fk_currency,
              fk_price_product,
              net_price,
              gross_price,
              price_data,
              price_data_checksum,
              price_product_store_key
            )
            WITH RECURSIVE n(digit) AS (
                SELECT 0 as digit
                UNION ALL
                SELECT 1 + digit FROM n WHERE digit < ?
            ), records AS (
                SELECT
                   input.id_store,
                   input.id_currency,
                   input.%3\$s,
                   input.id_price_product,
                   input.net_price,
                   input.gross_price,
                   input.price_data,
                   input.price_data_checksum,
                   input.price_product_store_key,
                   spy_price_product_store.id_price_product_store as idPriceProductStore
                FROM
                    (
                       SELECT
                            NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.id_stores, ',', n.digit + 1), ',', -1), '') as id_store,
                            NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.id_currencies, ',', n.digit + 1), ',', -1), '') as id_currency,
                            NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.%3\$ss, ',', n.digit + 1), ',', -1), '') as %3\$s,
                            NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.id_price_products, ',', n.digit + 1), ',', -1), '') as id_price_product,
                            NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.gross_prices, ',', n.digit + 1), ',', -1), '') as gross_price,
                            NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.net_prices, ',', n.digit + 1), ',', -1), '') as net_price,
                            REPLACE(NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.price_datas, ',', n.digit + 1), ',', -1), ''), '|', ',') as price_data,
                            NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.price_data_checksums, ',', n.digit + 1), ',', -1), '') as price_data_checksum,
                            NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.price_product_store_keys, ',', n.digit + 1), ',', -1), '') as price_product_store_key

                       FROM (
                            SELECT ? as id_stores,
                                   ? as id_currencies,
                                   ? as %3\$ss,
                                   ? as id_price_products,
                                   ? as gross_prices,
                                   ? as net_prices,
                                   ? as price_datas,
                                   ? as price_data_checksums,
                                   ? as price_product_store_keys
                       ) temp
                       INNER JOIN n
                          ON LENGTH(REPLACE(id_stores, ',', '')) <= LENGTH(id_stores) - n.digit
                            AND LENGTH(REPLACE(id_currencies, ',', '')) <= LENGTH(id_currencies) - n.digit
                            AND LENGTH(REPLACE(%3\$ss, ',', '')) <= LENGTH(%3\$ss) - n.digit
                            AND LENGTH(REPLACE(id_price_products, ',', '')) <= LENGTH(id_price_products) - n.digit
                            AND LENGTH(REPLACE(gross_prices, ',', '')) <= LENGTH(gross_prices) - n.digit
                            AND LENGTH(REPLACE(price_datas, ',', '')) <= LENGTH(price_datas) - n.digit
                            AND LENGTH(REPLACE(price_data_checksums, ',', '')) <= LENGTH(price_data_checksums) - n.digit
                            AND LENGTH(REPLACE(net_prices, ',', '')) <= LENGTH(net_prices) - n.digit
                            AND LENGTH(REPLACE(price_product_store_keys, ',', '')) <= LENGTH(price_product_store_keys) - n.digit
                    ) input
                    LEFT JOIN spy_price_product_store ON (
                        spy_price_product_store.fk_price_product = input.id_price_product AND
                        spy_price_product_store.fk_currency = input.id_currency AND
                        spy_price_product_store.fk_store = input.id_store AND
                        spy_price_product_store.price_product_store_key = input.price_product_store_key
                    )
                )
                (
                SELECT
                    idPriceProductStore,
                    id_store,
                    id_currency,
                    id_price_product,
                    net_price,
                    gross_price,
                    price_data,
                    price_data_checksum,
                    price_product_store_key
                FROM records
                )
                ON DUPLICATE KEY UPDATE gross_price = records.gross_price,
                  net_price = records.net_price,
                  price_data = records.price_data,
                  price_data_checksum = records.price_data_checksum,
                  price_product_store_key = records.price_product_store_key
                RETURNING id_price_product_store";

        return sprintf($sql, $tableName, $foreignKey, $idProduct);
    }

    /**
     * @return string
     */
    public function createPriceProductDefaultSql(): string
    {
        return '
            INSERT INTO spy_price_product_default (
              id_price_product_default,
              fk_price_product_store
            )
            WITH RECURSIVE n(digit) AS (
                SELECT 0 as digit
                UNION ALL
                SELECT 1 + digit FROM n WHERE digit < ?
            ), records AS (
                SELECT
                  input.id_price_product_store,
                  spy_price_product_default.id_price_product_default as price_product_default_id
                FROM (
                       SELECT
                            NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.id_price_product_stores, \',\', n.digit + 1), \',\', -1), \'\') as id_price_product_store
                       FROM (
                            SELECT ? as id_price_product_stores
                       ) temp
                       INNER JOIN n
                          ON LENGTH(REPLACE(id_price_product_stores, \',\', \'\')) <= LENGTH(id_price_product_stores) - n.digit
                ) input
                LEFT JOIN spy_price_product_default ON spy_price_product_default.fk_price_product_store = input.id_price_product_store
            )
            (
            SELECT
                price_product_default_id,
                records.id_price_product_store
                FROM records
            )
            ON DUPLICATE KEY UPDATE fk_price_product_store = records.id_price_product_store';
    }

    /**
     * @return string
     */
    public function convertStoreNameToId(): string
    {
        return '
            WITH RECURSIVE n(digit) AS (
                SELECT 0 as digit
                UNION ALL
                SELECT 1 + digit FROM n WHERE digit < ?
            ), records AS (
                SELECT
                  input.store,
                  spy_store.id_store as id_store
                FROM
                    (
                       SELECT
                            NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.stores, \',\', n.digit + 1), \',\', -1), \'\') as store
                       FROM (
                            SELECT ? as stores
                       ) temp
                       INNER JOIN n
                            ON LENGTH(REPLACE(stores, \',\', \'\')) <= LENGTH(stores) - n.digit
                    ) input
                    LEFT JOIN spy_store ON spy_store.name = input.store
            ) SELECT records.id_store FROM records';
    }

    /**
     * @return string
     */
    public function convertCurrencyNameToId(): string
    {
        return '
            WITH RECURSIVE n(digit) AS (
                    SELECT 0 as digit
                    UNION ALL
                    SELECT 1 + digit FROM n WHERE digit < ?
                ), records AS (
                SELECT
                  input.currency,
                  spy_currency.id_currency as id_currency
                FROM
                    (
                       SELECT
                            NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.currencies, \',\', n.digit + 1), \',\', -1), \'\') as currency
                       FROM (
                            SELECT ? as currencies
                       ) temp
                       INNER JOIN n
                            ON LENGTH(REPLACE(currencies, \',\', \'\')) <= LENGTH(currencies) - n.digit
                    ) input
                LEFT JOIN spy_currency ON spy_currency.code = input.currency
            ) SELECT records.id_currency FROM records;';
    }

    /**
     * @param string $tableName
     * @param string $fieldName
     *
     * @return string
     */
    public function convertProductSkuToId(string $tableName, string $fieldName): string
    {
        $sql = "
            WITH RECURSIVE n(digit) AS (
                SELECT 0 as digit
                UNION ALL
                SELECT 1 + digit FROM n WHERE digit < ?
            ), records AS (
                SELECT
                  input.sku,
                  %1\$s.%2\$s as %2\$s
                FROM (
                   SELECT
                        NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.skus, ',', n.digit + 1), ',', -1), '') as sku
                   FROM (
                        SELECT ? as skus
                   ) temp
                   INNER JOIN n
                        ON LENGTH(REPLACE(skus, ',', '')) <= LENGTH(skus) - n.digit
                ) input
                LEFT JOIN %1\$s ON %1\$s.sku = input.sku
            ) SELECT records.%2\$s FROM records";

        return sprintf($sql, $tableName, $fieldName);
    }

    /**
     * @return string
     */
    public function collectPriceTypes(): string
    {
        return '
            WITH RECURSIVE n(digit) AS (
                SELECT 0 as digit
                UNION ALL
                SELECT 1 + digit FROM n WHERE digit < ?
            ), records AS (
                SELECT
                  input.price_type,
                  spy_price_type.id_price_type
                FROM (
                   SELECT
                        NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.price_types, \',\', n.digit + 1), \',\', -1), \'\') as price_type
                   FROM (
                        SELECT ? as price_types
                   ) temp
                   INNER JOIN n
                      ON LENGTH(REPLACE(price_types, \',\', \'\')) <= LENGTH(price_types) - n.digit
                ) input
                LEFT JOIN spy_price_type ON spy_price_type.name = input.price_type
            )
            (SELECT records.id_price_type FROM records)';
    }
}
