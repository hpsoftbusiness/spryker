<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\AvailabilityStorage\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer;
use Spryker\Client\AvailabilityStorage\Mapper\AvailabilityStorageMapper as SprykerAvailabilityStorageMapper;

class AvailabilityStorageMapper extends SprykerAvailabilityStorageMapper
{
    protected const KEY_CONCRETE_ID = 'id_availability';

    /**
     * @param array $productConcreteAvailabilityDataItems
     * @param \ArrayObject|\Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer[] $productConcreteAvailabilityTransfers
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer[]
     */
    protected function mapProductConcreteAvailabilityDataToProductConcreteAvailabilityTransfers(
        array $productConcreteAvailabilityDataItems,
        ArrayObject $productConcreteAvailabilityTransfers
    ): ArrayObject {
        foreach ($productConcreteAvailabilityDataItems as $productConcreteAvailabilityDataItem) {
            $productConcreteAvailabilityTransfer = (new ProductConcreteAvailabilityTransfer())
                ->fromArray($productConcreteAvailabilityDataItem, true)
                ->setSku($productConcreteAvailabilityDataItem[static::KEY_SKU])
                ->setAvailability($productConcreteAvailabilityDataItem[static::KEY_QUANTITY])
                ->setIsNeverOutOfStock((bool)$productConcreteAvailabilityDataItem[static::KEY_IS_NEVER_OUT_OF_STOCK])
                ->setIdProductConcrete((int)$productConcreteAvailabilityDataItem[static::KEY_CONCRETE_ID]);

            $productConcreteAvailabilityTransfers->append($productConcreteAvailabilityTransfer);
        }

        return $productConcreteAvailabilityTransfers;
    }
}
