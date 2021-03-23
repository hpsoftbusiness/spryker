<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\AvailabilityGui\Communication\Table;

use Spryker\DecimalObject\Decimal;
use Spryker\Zed\Availability\Persistence\AvailabilityQueryContainer;
use Spryker\Zed\AvailabilityGui\Communication\Helper\AvailabilityHelperInterface;
use Spryker\Zed\AvailabilityGui\Communication\Table\AvailabilityTable as SprykerAvailabilityTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class AvailabilityTable extends SprykerAvailabilityTable
{
    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array
     */
    protected function prepareData(TableConfiguration $config): array
    {
        $result = [];

        $queryResult = $this->runQuery(
            $this->queryProductAbstractAvailability->filterByIsRemoved(false),
            $config,
            true
        );

        foreach ($queryResult as $productItem) {
            $isBundleProduct = $this->availabilityHelper->isBundleProduct(
                $productItem[AvailabilityQueryContainer::ID_PRODUCT]
            );

            $isNeverOutOfStock = $this->availabilityHelper->isNeverOutOfStock(
                $productItem[AvailabilityHelperInterface::CONCRETE_NEVER_OUT_OF_STOCK_SET] ?? static::NEVER_OUT_OF_STOCK_DEFAULT_VALUE
            );

            $result[] = [
                AvailabilityHelperInterface::CONCRETE_SKU => $productItem[AvailabilityHelperInterface::CONCRETE_SKU],
                AvailabilityHelperInterface::CONCRETE_NAME => $productItem[AvailabilityHelperInterface::CONCRETE_NAME],
                AvailabilityHelperInterface::CONCRETE_AVAILABILITY => $isNeverOutOfStock ? static::NOT_APPLICABLE : (new Decimal(
                    $productItem[AvailabilityHelperInterface::CONCRETE_AVAILABILITY] ?? 0
                ))->trim(),
                AvailabilityHelperInterface::STOCK_QUANTITY => (new Decimal(
                    $productItem[AvailabilityHelperInterface::STOCK_QUANTITY] ?? 0
                ))->trim(),
                AvailabilityHelperInterface::RESERVATION_QUANTITY => $isBundleProduct ? static::NOT_APPLICABLE : $this->calculateReservation(
                    $productItem
                )->trim(),
                static::IS_BUNDLE_PRODUCT => $this->generateLabel($isBundleProduct ? 'Yes' : 'No', null),
                AvailabilityHelperInterface::CONCRETE_NEVER_OUT_OF_STOCK_SET => $this->generateLabel(
                    $isNeverOutOfStock ? 'Yes' : 'No',
                    null
                ),
                static::TABLE_COL_ACTION => $this->createButtons($productItem, $isBundleProduct),
            ];
        }

        return $result;
    }
}
