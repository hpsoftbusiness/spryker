<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\Model\ProductStock\Writer;

use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface;

class ProductStockBulkPdoMariaDbDataSetWriter extends AbstractProductStockBulkDataSetWriter implements DataSetWriterInterface
{
    /**
     * @return void
     */
    protected function persistStockEntities(): void
    {
        $names = $this->dataFormatter->getCollectionDataByKey(static::$stockCollection, self::COLUMN_NAME);
        $uniqueNames = array_unique($names);
        $name = $this->dataFormatter->formatStringList($uniqueNames);

        $sql = $this->productStockSql->createStockSQL();

        if (count($names) > 0) {
            $this->propelExecutor->execute(
                $sql,
                [
                    count($uniqueNames),
                    $name,
                ],
                false
            );
        }
    }

    /**
     * @return void
     */
    protected function persistStockProductEntities(): void
    {
        $sku = $this->dataFormatter->formatStringList(
            $this->dataFormatter->getCollectionDataByKey(static::$stockProductCollection, self::COLUMN_CONCRETE_SKU)
        );

        $stockName = $this->dataFormatter->formatStringList(
            $this->dataFormatter->getCollectionDataByKey(static::$stockCollection, self::COLUMN_NAME)
        );
        $quantity = $this->dataFormatter->formatStringList(
            $this->dataFormatter->getCollectionDataByKey(static::$stockProductCollection, self::COLUMN_QUANTITY)
        );
        $isNeverOutOfStock = $this->dataFormatter->formatStringList(
            $this->dataFormatter->getCollectionDataByKey(static::$stockProductCollection, self::COLUMN_IS_NEVER_OUT_OF_STOCK)
        );

        $sql = $this->productStockSql->createStockProductSQL();
        $parameters = [
            count(static::$stockProductCollection),
            $sku,
            $stockName,
            $quantity,
            $isNeverOutOfStock,
        ];

        $this->propelExecutor->execute($sql, $parameters);
    }

    /**
     * @return void
     */
    protected function persistAvailability(): void
    {
        $skus = $this->dataFormatter->getCollectionDataByKey(static::$stockProductCollection, static::COLUMN_CONCRETE_SKU);
        $storeTransfer = $this->storeFacade->getCurrentStore();

        $concreteSkusToAbstractMap = $this->mapConcreteSkuToAbstractSku($skus);
        $reservationItems = $this->getReservationsBySkus($skus);

        $this->updateAvailability($skus, $storeTransfer, $concreteSkusToAbstractMap, $reservationItems);
    }

    /**
     * @param array $skus
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param array $concreteSkusToAbstractMap
     * @param array $reservationItems
     *
     * @return void
     */
    protected function updateAvailability(
        array $skus,
        StoreTransfer $storeTransfer,
        array $concreteSkusToAbstractMap,
        array $reservationItems
    ): void {
        $stockProductsForStore = $this->getStockProductBySkusAndStore($skus, $storeTransfer);
        $concreteAvailabilityData = $this->prepareConcreteAvailabilityData($stockProductsForStore, $reservationItems);
        $abstractAvailabilityData = $this->prepareAbstractAvailabilityData($concreteAvailabilityData, $concreteSkusToAbstractMap);

        if (count($abstractAvailabilityData) > 0) {
            $abstractAvailabilityQueryParams = [
                count($abstractAvailabilityData),
                $this->dataFormatter->formatStringList(array_column($abstractAvailabilityData, static::KEY_SKU)),
                $this->dataFormatter->formatStringList(array_column($abstractAvailabilityData, static::KEY_QUANTITY)),
                $this->dataFormatter->formatStringList(array_fill(0, count($abstractAvailabilityData), $storeTransfer->getIdStore())),
            ];

            $availabilityAbstractIds = $this->propelExecutor->execute(
                $this->productStockSql->createAbstractAvailabilitySQL(),
                $abstractAvailabilityQueryParams
            );

            $this->collectAvailabilityAbstractIds($availabilityAbstractIds);
        }

        if (count($concreteAvailabilityData) > 0) {
            $availabilityQueryParams = [
                count($concreteAvailabilityData),
                $this->dataFormatter->formatStringList(array_column($concreteAvailabilityData, static::KEY_SKU)),
                $this->dataFormatter->formatStringList(array_column($concreteAvailabilityData, static::KEY_QUANTITY)),
                $this->dataFormatter->formatBooleanList(array_column($concreteAvailabilityData, static::KEY_IS_NEVER_OUT_OF_STOCK)),
                $this->dataFormatter->formatStringList(array_fill(0, count($concreteAvailabilityData), $storeTransfer->getIdStore())),
            ];

            $this->propelExecutor->execute($this->productStockSql->createAvailabilitySQL(), $availabilityQueryParams);
        }
    }
}
