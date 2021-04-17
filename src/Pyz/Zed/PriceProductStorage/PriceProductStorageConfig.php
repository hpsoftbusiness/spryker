<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\PriceProductStorage;

use Spryker\Zed\PriceProductStorage\PriceProductStorageConfig as SprykerPriceProductStorageConfig;

class PriceProductStorageConfig extends SprykerPriceProductStorageConfig
{
    public const PUBLISH_PRODUCT_ABSTRACT_PRICE = 'publish.product_abstract_price';
    public const PUBLISH_PRODUCT_CONCRETE_PRICE = 'publish.product_concrete_price';

    public const CHUNK_SIZE = 500;

    /**
     * @return string|null
     */
    public function getPriceProductAbstractEventQueueName(): ?string
    {
        return static::PUBLISH_PRODUCT_ABSTRACT_PRICE;
    }

    /**
     * @return string|null
     */
    public function getPriceProductConcreteEventQueueName(): ?string
    {
        return static::PUBLISH_PRODUCT_CONCRETE_PRICE;
    }

    /**
     * @return int
     */
    public function getChunkSize(): int
    {
        return static::CHUNK_SIZE;
    }
}
