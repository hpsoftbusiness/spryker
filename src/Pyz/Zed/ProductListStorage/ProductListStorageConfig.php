<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductListStorage;

use Pyz\Zed\ProductStorage\ProductStorageConfig;
use Pyz\Zed\Synchronization\SynchronizationConfig;
use Spryker\Zed\ProductListStorage\ProductListStorageConfig as SprykerProductListStorageConfig;

class ProductListStorageConfig extends SprykerProductListStorageConfig
{
    public const PUBLISH_PRODUCT_LIST = ProductStorageConfig::PUBLISH_PRODUCT_CONCRETE;

    /**
     * @return string|null
     */
    public function getProductAbstractProductListEventQueueName(): ?string
    {
        return static::PUBLISH_PRODUCT_LIST;
    }

    /**
     * @return string|null
     */
    public function getProductConcreteProductListEventQueueName(): ?string
    {
        return static::PUBLISH_PRODUCT_LIST;
    }

    /**
     * @return string|null
     */
    public function getProductAbstractProductListSynchronizationPoolName(): ?string
    {
        return SynchronizationConfig::DEFAULT_SYNCHRONIZATION_POOL_NAME;
    }

    /**
     * @return string|null
     */
    public function getProductConcreteProductListSynchronizationPoolName(): ?string
    {
        return SynchronizationConfig::DEFAULT_SYNCHRONIZATION_POOL_NAME;
    }
}
