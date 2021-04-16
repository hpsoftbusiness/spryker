<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\AvailabilityStorage\Business\Storage\Cte;

use Pyz\Zed\Propel\Business\CTE\PostgresDataFormatterTrait;

class AvailabilityStoragePgDbCte implements AvailabilityStorageCteInterface
{
    use PostgresDataFormatterTrait;

    /**
     * @param array $data
     *
     * @return array
     */
    public function buildParams(array $data): array
    {
        $productAbstractForeignKeys = $this->formatPostgresArray(array_column($data, 'fk_product_abstract'));
        $productAbstractAvailabilityForeignKeys = $this->formatPostgresArray(array_column($data, 'fk_availability_abstract'));
        $stores = $this->formatPostgresArrayString(array_column($data, 'store'));
        $formattedData = $this->formatPostgresArrayFromJson(array_column($data, 'data'));
        $keys = $this->formatPostgresArrayString(array_column($data, 'key'));

        return [
            $productAbstractForeignKeys,
            $productAbstractAvailabilityForeignKeys,
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
            WITH records AS (
                SELECT
                  input.fk_product_abstract,
                  input.fk_availability_abstract,
                  input.store,
                  input.data,
                  input.key,
                  id_availability_storage
                FROM (
                       SELECT
                         unnest(? :: INTEGER []) AS fk_product_abstract,
                         unnest(? :: INTEGER []) AS fk_availability_abstract,
                         unnest(? :: VARCHAR []) AS store,
                         json_array_elements(?) AS data,
                         unnest(? :: VARCHAR []) AS key
                     ) input
                  LEFT JOIN spy_availability_storage ON spy_availability_storage.key = input.key
                ),
                updated AS (
                UPDATE spy_availability_storage
                SET
                  fk_product_abstract = records.fk_product_abstract,
                  fk_availability_abstract = records.fk_availability_abstract,
                  store = records.store,
                  data = records.data,
                  key = records.key,
                  updated_at = now()
                FROM records
                WHERE records.key = spy_availability_storage.key
                RETURNING spy_availability_storage.id_availability_storage
              ),
                inserted AS (
                INSERT INTO spy_availability_storage(
                  id_availability_storage,
                  fk_product_abstract,
                  fk_availability_abstract,
                  store,
                  data,
                  key,
                  created_at,
                  updated_at
                ) (
                  SELECT
                    nextval('spy_availability_storage_pk_seq'),
                    fk_product_abstract,
                    fk_availability_abstract,
                    store,
                    data,
                    key,
                    now(),
                    now()
                  FROM records
                  WHERE id_availability_storage is null
                ) RETURNING spy_availability_storage.id_availability_storage
              )
            SELECT updated.id_availability_storage FROM updated
            UNION ALL
            SELECT inserted.id_availability_storage FROM inserted;
        SQL;
    }
}
