<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductImageStorage;

use Pyz\Zed\Synchronization\SynchronizationConfig;
use Spryker\Shared\Publisher\PublisherConfig;
use Spryker\Zed\ProductImageStorage\ProductImageStorageConfig as SprykerProductImageStorageConfig;

class ProductImageStorageConfig extends SprykerProductImageStorageConfig
{
    public const PUBLISH_PRODUCT_IMAGE = 'publish.product_image';

    protected const CHUNK_SIZE = 1000;

    /**
     * @return string|null
     */
    public function getProductImageSynchronizationPoolName(): ?string
    {
        return SynchronizationConfig::DEFAULT_SYNCHRONIZATION_POOL_NAME;
    }

    /**
     * @return string|null
     */
    public function getProductImageAbstractEventQueueName(): ?string
    {
        return PublisherConfig::PUBLISH_QUEUE;
    }

    /**
     * @return string|null
     */
    public function getProductImageConcreteEventQueueName(): ?string
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
