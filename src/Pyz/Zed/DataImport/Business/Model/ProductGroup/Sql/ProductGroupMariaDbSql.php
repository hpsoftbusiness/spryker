<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\Model\ProductGroup\Sql;

class ProductGroupMariaDbSql implements ProductGroupSqlInterface
{
    /**
     * @return string
     */
    public function createProductGroupSQL(): string
    {
        return '
            INSERT INTO spy_product_group (
                id_product_group,
                product_group_key
            )
            WITH RECURSIVE n(digit) AS (
                SELECT 0 as digit
                UNION ALL
                SELECT 1 + digit FROM n WHERE digit < ?
                ),
                records AS (
                    SELECT
                      input.product_group_key as productGroupKey,
                      id_product_group as idProductGroup,
                      spy_product_group.product_group_key as spyProductGroupKey
                    FROM (
                           SELECT DISTINCT
                                NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.product_group_keys, \',\', n.digit + 1), \',\', -1), \'\') as product_group_key
                           FROM (
                                SELECT ? as product_group_keys
                           ) temp
                           INNER JOIN n
                              ON LENGTH(REPLACE(product_group_keys, \',\', \'\')) <= LENGTH(product_group_keys) - n.digit
                         ) input
                     LEFT JOIN spy_product_group ON spy_product_group.product_group_key = input.product_group_key
                )
                (
                  SELECT
                    idProductGroup,
                    productGroupKey
                  FROM records
                )
            ON DUPLICATE KEY UPDATE product_group_key = records.productGroupKey';
    }

    /**
     * @return string
     */
    public function createProductAbstractGroupSQL(): string
    {
        return '
            INSERT INTO spy_product_abstract_group (
              fk_product_abstract,
              fk_product_group,
              position
            )
            WITH RECURSIVE n(digit) AS (
                SELECT 0 as digit
                UNION ALL
                SELECT 1 + digit FROM n WHERE digit < ?
            ), records AS (
                SELECT
                  input.sku,
                  input.group_key,
                  input.position,
                  id_product_abstract as idProductAbstract,
                  id_product_group as idProductGroup
                FROM (
                   SELECT DISTINCT
                        NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.skus, \',\', n.digit + 1), \',\', -1), \'\') as sku,
                        NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.group_keys, \',\', n.digit + 1), \',\', -1), \'\') as group_key,
                        NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.positions, \',\', n.digit + 1), \',\', -1), \'\') as position
                   FROM (
                        SELECT ? as skus,
                               ? as group_keys,
                               ? as positions
                   ) temp
                   INNER JOIN n
                      ON LENGTH(REPLACE(skus, \',\', \'\')) <= LENGTH(skus) - n.digit
                        AND LENGTH(REPLACE(group_keys, \',\', \'\')) <= LENGTH(group_keys) - n.digit
                        AND LENGTH(REPLACE(positions, \',\', \'\')) <= LENGTH(positions) - n.digit
                 ) input
                INNER JOIN spy_product_abstract ON spy_product_abstract.sku = input.sku
                INNER JOIN spy_product_group ON spy_product_group.product_group_key = input.group_key
            )
            (
              SELECT
                idProductAbstract,
                idProductGroup,
                records.position
              FROM records
            )
            ON DUPLICATE KEY UPDATE position = records.position
            RETURNING fk_product_abstract';
    }
}
