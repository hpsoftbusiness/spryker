<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductStorage;

use Pyz\Zed\Synchronization\SynchronizationConfig;
use Spryker\Shared\Publisher\PublisherConfig;
use Spryker\Zed\ProductStorage\ProductStorageConfig as SprykerProductStorageConfig;

class ProductStorageConfig extends SprykerProductStorageConfig
{
    public const PUBLISH_PRODUCT_ABSTRACT = 'publish.product_abstract';
    public const PUBLISH_PRODUCT_CONCRETE = 'publish.product_concrete';

    protected const CHUNK_SIZE = 1000;

    /**
     * @return string|null
     */
    public function getProductConcreteSynchronizationPoolName(): ?string
    {
        return SynchronizationConfig::DEFAULT_SYNCHRONIZATION_POOL_NAME;
    }

    /**
     * @return string|null
     */
    public function getProductAbstractSynchronizationPoolName(): ?string
    {
        return SynchronizationConfig::DEFAULT_SYNCHRONIZATION_POOL_NAME;
    }

    /**
     * @return string|null
     */
    public function getProductConcreteEventQueueName(): ?string
    {
        return PublisherConfig::PUBLISH_QUEUE;
    }

    /**
     * @return string|null
     */
    public function getProductAbstractEventQueueName(): ?string
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
