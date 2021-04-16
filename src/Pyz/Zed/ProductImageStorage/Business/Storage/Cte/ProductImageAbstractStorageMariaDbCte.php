<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductImageStorage\Business\Storage\Cte;

use Pyz\Zed\Propel\Business\CTE\MariaDbDataFormatterTrait;

class ProductImageAbstractStorageMariaDbCte implements ProductImageStorageCteInterface
{
    use MariaDbDataFormatterTrait;

    /**
     * @param array $data
     *
     * @return array
     */
    public function buildParams(array $data): array
    {
        $foreignKeysRaw = array_column($data, 'fk_product_abstract');
        $rowsCount = count($foreignKeysRaw);
        $foreignKeys = $this->formatStringList($foreignKeysRaw);
        $locales = $this->formatStringList(array_column($data, 'locale'));
        $formattedData = $this->formatDataStringList(array_column($data, 'data'));
        $keys = $this->formatStringList(array_column($data, 'key'));

        return [
            (string)$rowsCount,
            $foreignKeys,
            $locales,
            $formattedData,
            $keys,
        ];
    }

    /**
     * @return string
     */
    public function getSql(): string
    {
        return <<<SQL
            INSERT INTO spy_product_abstract_image_storage(
                id_product_abstract_image_storage,
                fk_product_abstract,
                locale,
                data,
                `key`,
                created_at,
                updated_at
            )
            WITH RECURSIVE n(digit) AS (
                SELECT 0 as digit
                UNION ALL
                SELECT 1 + digit FROM n WHERE digit < ?
            ), records AS (
                SELECT
                    input.fk_product_abstract,
                    input.locale,
                    input.data,
                    input.key,
                    id_product_abstract_image_storage
                FROM (
                         SELECT DISTINCT
                             NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.fk_product_abstracts, ',', n.digit + 1), ',', -1), '') as fk_product_abstract,
                             NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.locales, ',', n.digit + 1), ',', -1), '') as locale,
                             REPLACE(NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.datas, ',', n.digit + 1), ',', -1), ''), '|', ',') as data,
                             NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.keys, ',', n.digit + 1), ',', -1), '') as `key`
                         FROM (
                             SELECT ? as fk_product_abstracts,
                             ? as locales,
                             ? as datas,
                             ? as `keys`
                             ) temp
                             INNER JOIN n
                         ON LENGTH(REPLACE(fk_product_abstracts, ',', '')) <= LENGTH(fk_product_abstracts) - n.digit
                                    AND LENGTH(REPLACE(locales, ',', '')) <= LENGTH(locales) - n.digit
                                    AND LENGTH(REPLACE(datas, ',', '')) <= LENGTH(datas) - n.digit
                                    AND LENGTH(REPLACE(`keys`, ',', '')) <= LENGTH(`keys`) - n.digit
                             ) input
                            LEFT JOIN spy_product_abstract_image_storage ON spy_product_abstract_image_storage.key = input.key
                        )
                        (
                          SELECT
                            id_product_abstract_image_storage,
                            fk_product_abstract,
                            locale,
                            data,
                            `key`,
                            now(),
                            now()
                          FROM records
                        )
                        ON DUPLICATE KEY UPDATE
                            fk_product_abstract = records.fk_product_abstract,
                            locale = records.locale,
                            data = records.data,
                            `key` = records.key,
                            updated_at = now()
        SQL;
    }
}
