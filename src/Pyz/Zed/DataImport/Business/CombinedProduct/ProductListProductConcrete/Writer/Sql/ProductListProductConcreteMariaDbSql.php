<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\CombinedProduct\ProductListProductConcrete\Writer\Sql;

class ProductListProductConcreteMariaDbSql implements ProductListProductConcreteSqlInterface
{
    /**
     * @return string
     */
    public function createProductListProductConcreteSQL(): string
    {
        return '
            INSERT INTO spy_product_list_product_concrete (
                fk_product,
                fk_product_list
            )
            WITH RECURSIVE n(digit) AS (
                SELECT 0 as digit
                UNION ALL
                SELECT 1 + digit FROM n WHERE digit < ?
                ),
                records AS (
                    SELECT
                      input.concrete_sku,
                      input.product_list_key,
                      spy_product.id_product as idProduct,
                      spy_product_list.id_product_list as idProductList
                    FROM (
                       SELECT DISTINCT
                            NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.concrete_skus, \',\', n.digit + 1), \',\', -1), \'\') as concrete_sku,
                            NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.product_list_keys, \',\', n.digit + 1), \',\', -1), \'\') as product_list_key
                       FROM (
                            SELECT ? as concrete_skus,
                                   ? as product_list_keys
                       ) temp
                       INNER JOIN n
                          ON LENGTH(REPLACE(concrete_skus, \',\', \'\')) <= LENGTH(concrete_skus) - n.digit
                            AND LENGTH(REPLACE(product_list_keys, \',\', \'\')) <= LENGTH(product_list_keys) - n.digit
                    ) input
                        INNER JOIN spy_product ON spy_product.sku = input.concrete_sku
                        INNER JOIN spy_product_list ON spy_product_list.key = input.product_list_key
                )
                (
                  SELECT
                    idProduct,
                    idProductList
                  FROM records
                )
            ON DUPLICATE KEY UPDATE fk_product = records.idProduct,
              fk_product_list = records.idProductList
            RETURNING fk_product as id_product_concrete
        ';
    }
}
