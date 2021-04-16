<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\Model\ProductAbstractStore\Writer;

class ProductAbstractStoreBulkPdoMariaDbDataSetWriter extends AbstractProductAbstractStoreBulkDataSetWriter
{
    /**
     * @return void
     */
    protected function persistAbstractProductStoreEntities(): void
    {
        $rawAbstractSku = $this->dataFormatter->getCollectionDataByKey(static::$productAbstractStoreCollection, 'product_abstract_sku');
        $abstractSku = $this->dataFormatter->formatStringList($rawAbstractSku);
        $rowsCount = count($rawAbstractSku);

        $storeName = $this->dataFormatter->formatStringList(
            $this->dataFormatter->getCollectionDataByKey(static::$productAbstractStoreCollection, self::COLUMN_STORE_NAME),
            $rowsCount
        );

        $sql = $this->productAbstractStoreSql->createAbstractProductStoreSQL();
        $parameters = [
            $rowsCount,
            $abstractSku,
            $storeName,
        ];

        $this->propelExecutor->execute($sql, $parameters, false);
    }
}
