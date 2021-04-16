<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\Model\ProductStock\Reader;

interface ProductStockReaderInterface
{
    /**
     * @param int[] $availabilityAbstractIds
     *
     * @return int[]
     */
    public function getProductAbstractIdsByAvailabilityAbstractIds(array $availabilityAbstractIds): array;
}
