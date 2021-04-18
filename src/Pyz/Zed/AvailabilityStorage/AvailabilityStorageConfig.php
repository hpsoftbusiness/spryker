<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\AvailabilityStorage;

use Spryker\Zed\AvailabilityStorage\AvailabilityStorageConfig as SprykerAvailabilityStorageConfig;

class AvailabilityStorageConfig extends SprykerAvailabilityStorageConfig
{
    public const PUBLISH_AVAILABILITY = 'publish.availability';

    protected const DEFAULT_CHUNK_SIZE = 1000;

    /**
     * @return string|null
     */
    public function getEventQueueName(): ?string
    {
        return static::PUBLISH_AVAILABILITY;
    }

    /**
     * @return int
     */
    public function getChunkSize(): int
    {
        return static::DEFAULT_CHUNK_SIZE;
    }
}
