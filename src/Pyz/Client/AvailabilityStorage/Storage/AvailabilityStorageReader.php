<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\AvailabilityStorage\Storage;

use Generated\Shared\Transfer\ProductAbstractAvailabilityTransfer;
use Spryker\Client\AvailabilityStorage\Storage\AvailabilityStorageReader as SprykerAvailabilityStorageReader;

class AvailabilityStorageReader extends SprykerAvailabilityStorageReader
{
    /**
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\ProductAbstractAvailabilityTransfer|null
     */
    public function findAbstractProductAvailability(int $idProductAbstract): ?ProductAbstractAvailabilityTransfer
    {
        $availabilityStorageData = $this->storageClient->get(
            $this->generateKey($idProductAbstract)
        );

        if (!$availabilityStorageData) {
            return null;
        }

        return $this->availabilityStorageMapper
            ->mapAvailabilityStorageDataToProductAbstractAvailabilityTransfer(
                $availabilityStorageData,
                (new ProductAbstractAvailabilityTransfer())->setIdProductAbstract($idProductAbstract)
            );
    }
}
