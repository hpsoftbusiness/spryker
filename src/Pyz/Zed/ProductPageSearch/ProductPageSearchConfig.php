<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductPageSearch;

use Pyz\Shared\Catalog\CatalogConfig;
use Spryker\Shared\Publisher\PublisherConfig;
use Spryker\Zed\ProductPageSearch\ProductPageSearchConfig as SprykerProductPageSearchConfig;

class ProductPageSearchConfig extends SprykerProductPageSearchConfig
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return bool
     */
    public function isProductAbstractAddToCartEnabled(): bool
    {
        return true;
    }

    /**
     * @api
     *
     * @return string|null
     */
    public function getProductPageEventQueueName(): ?string
    {
        return PublisherConfig::PUBLISH_QUEUE;
    }

    /**
     * @api
     *
     * @return string|null
     */
    public function getProductConcretePageEventQueueName(): ?string
    {
        return PublisherConfig::PUBLISH_QUEUE;
    }

    /**
     * @return string
     */
    public function getProductAbstractSellableFacetName(): string
    {
        return CatalogConfig::PRODUCT_ABSTRACT_SELLABLE_FACET_NAME;
    }
}
