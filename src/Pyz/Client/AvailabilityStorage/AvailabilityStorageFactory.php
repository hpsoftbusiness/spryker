<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\AvailabilityStorage;

use Pyz\Client\AvailabilityStorage\Mapper\AvailabilityStorageMapper;
use Pyz\Client\AvailabilityStorage\Storage\AvailabilityStorageReader;
use Spryker\Client\AvailabilityStorage\AvailabilityStorageFactory as SprykerAvailabilityStorageFactory;
use Spryker\Client\AvailabilityStorage\Mapper\AvailabilityStorageMapperInterface;
use Spryker\Client\AvailabilityStorage\Storage\AvailabilityStorageReaderInterface;

class AvailabilityStorageFactory extends SprykerAvailabilityStorageFactory
{
    /**
     * @return \Spryker\Client\AvailabilityStorage\Storage\AvailabilityStorageReaderInterface
     */
    public function createAvailabilityStorageReader(): AvailabilityStorageReaderInterface
    {
        return new AvailabilityStorageReader(
            $this->getStorage(),
            $this->getSynchronizationService(),
            $this->createAvailabilityStorageMapper()
        );
    }

    /**
     * @return \Spryker\Client\AvailabilityStorage\Mapper\AvailabilityStorageMapperInterface
     */
    public function createAvailabilityStorageMapper(): AvailabilityStorageMapperInterface
    {
        return new AvailabilityStorageMapper();
    }
}
