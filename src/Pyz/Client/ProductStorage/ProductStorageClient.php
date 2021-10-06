<?php
declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\ProductStorage;

use Spryker\Client\ProductStorage\ProductStorageClient as SprykerProductStorageClient;

/**
 * @method \Pyz\Client\ProductStorage\ProductStorageFactory getFactory()
 */
class ProductStorageClient extends SprykerProductStorageClient implements ProductStorageClientInterface
{
    /**
     * @param array $productAbstractIds
     * @param string $localeName
     * @param string $storeName
     *
     * @return array
     */
    public function getBulkProductAbstractStorageDataByProductAbstractIdsForLocaleNameAndStoreWithoutRestrictions(array $productAbstractIds, string $localeName, string $storeName): array
    {
        return $this->getFactory()
            ->createProductAbstractStorageReaderWithoutRestrictions()
            ->getBulkProductAbstractStorageDataByProductAbstractIdsForLocaleNameAndStore($productAbstractIds, $localeName, $storeName);
    }
}
