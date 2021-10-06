<?php
declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\ProductStorage;

use Spryker\Client\ProductStorage\ProductStorageClientInterface as SprykerProductStorageClientInterface;

interface ProductStorageClientInterface extends SprykerProductStorageClientInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int[] $productAbstractIds
     * @param string $localeName
     * @param string $storeName
     *
     * @return array
     */
    public function getBulkProductAbstractStorageDataByProductAbstractIdsForLocaleNameAndStoreWithoutRestrictions(
        array $productAbstractIds,
        string $localeName,
        string $storeName
    ): array;
}
