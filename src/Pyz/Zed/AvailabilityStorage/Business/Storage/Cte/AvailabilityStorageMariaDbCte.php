<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\AvailabilityStorage\Business\Storage\Cte;

use Pyz\Zed\Propel\Business\CTE\MariaDbDataFormatterTrait;

class AvailabilityStorageMariaDbCte implements AvailabilityStorageCteInterface
{
    use MariaDbDataFormatterTrait;

    /**
     * @param array $data
     *
     * @return array
     */
    public function buildParams(array $data): array
    {
        $foreignKeysProductAbstractRaw = array_column($data, 'fk_product_abstract');
        $foreignKeysProductAvailabilityAbstractRaw = array_column($data, 'fk_availability_abstract');
        $rowsCount = count($foreignKeysProductAbstractRaw);

        $foreignProductAbstractKeys = $this->formatStringList($foreignKeysProductAbstractRaw);
        $foreignProductAbstractAvailabilityKeys = $this->formatStringList($foreignKeysProductAvailabilityAbstractRaw);
        $stores = $this->formatStringList(array_column($data, 'store'));
        $formattedData = $this->formatDataStringList(array_column($data, 'data'));
        $keys = $this->formatStringList(array_column($data, 'key'));

        return [
            (string)$rowsCount,
            $foreignProductAbstractKeys,
            $foreignProductAbstractAvailabilityKeys,
            $stores,
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
            INSERT INTO spy_availability_storage(
                id_availability_storage,
                fk_product_abstract,
                fk_availability_abstract,
                store,
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
                    input.fk_availability_abstract,
                    input.store,
                    input.data,
                    input.key,
                    id_availability_storage
                FROM (
                   SELECT DISTINCT
                        NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.fk_product_abstracts, ',', n.digit + 1), ',', -1), '') as fk_product_abstract,
                        NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.fk_availability_abstracts, ',', n.digit + 1), ',', -1), '') as fk_availability_abstract,
                        NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.stores, ',', n.digit + 1), ',', -1), '') as store,
                        REPLACE(NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.datas, ',', n.digit + 1), ',', -1), ''), '|', ',') as data,
                        NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.keys, ',', n.digit + 1), ',', -1), '') as `key`
                   FROM (
                        SELECT ? as fk_product_abstracts,
                               ? as fk_availability_abstracts,
                               ? as stores,
                               ? as datas,
                               ? as `keys`
                   ) temp
                   INNER JOIN n
                      ON LENGTH(REPLACE(fk_product_abstracts, ',', '')) <= LENGTH(fk_product_abstracts) - n.digit
                        AND LENGTH(REPLACE(fk_availability_abstracts, ',', '')) <= LENGTH(fk_availability_abstracts) - n.digit
                        AND LENGTH(REPLACE(stores, ',', '')) <= LENGTH(stores) - n.digit
                        AND LENGTH(REPLACE(datas, ',', '')) <= LENGTH(datas) - n.digit
                        AND LENGTH(REPLACE(`keys`, ',', '')) <= LENGTH(`keys`) - n.digit
                ) input
                LEFT JOIN spy_availability_storage ON spy_availability_storage.key = input.key
            )
            (
              SELECT
                id_availability_storage,
                fk_product_abstract,
                fk_availability_abstract,
                store,
                data,
                `key`,
                now(),
                now()
              FROM records
            )
            ON DUPLICATE KEY UPDATE
                fk_product_abstract = records.fk_product_abstract,
                fk_availability_abstract = records.fk_availability_abstract,
                store = records.store,
                data = records.data,
                `key` = records.key,
                updated_at = now()
        SQL;
    }
}
