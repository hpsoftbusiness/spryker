<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\UrlStorage;

use Pyz\Zed\Synchronization\SynchronizationConfig;
use Spryker\Shared\Publisher\PublisherConfig;
use Spryker\Zed\UrlStorage\UrlStorageConfig as SprykerUrlStorageConfig;

class UrlStorageConfig extends SprykerUrlStorageConfig
{
    public const PUBLISH_URL = 'publish.url';

    protected const CHUNK_SIZE = 1000;

    /**
     * @return string|null
     */
    public function getUrlSynchronizationPoolName(): ?string
    {
        return SynchronizationConfig::DEFAULT_SYNCHRONIZATION_POOL_NAME;
    }

    /**
     * @return string|null
     */
    public function getUrlRedirectSynchronizationPoolName(): ?string
    {
        return SynchronizationConfig::DEFAULT_SYNCHRONIZATION_POOL_NAME;
    }

    /**
     * @api
     *
     * @return string|null
     */
    public function getUrlEventQueueName(): ?string
    {
        return PublisherConfig::PUBLISH_QUEUE;
    }

    /**
     * @api
     *
     * @return string|null
     */
    public function getUrlRedirectEventQueueName(): ?string
    {
        return PublisherConfig::PUBLISH_QUEUE;
    }

    /**
     * @return int
     */
    public function getChunkSize(): int
    {
        return static::CHUNK_SIZE;
    }
}
