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
    public const PUBLISH_PRODUCT_ABSTRACT_PAGE = 'publish.product_abstract_page';
    public const PUBLISH_PRODUCT_CONCRETE_PAGE = 'publish.product_concrete_page';

    protected const CHUNK_SIZE = 500;

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

    /**
     * @return string
     */
    public function getProductAbstractDealFacetName(): string
    {
        return CatalogConfig::PRODUCT_ABSTRACT_DEAL_FACET_NAME;
    }

    /**
     * @return int
     */
    public function getChunkSize(): int
    {
        return static::CHUNK_SIZE;
    }

    /**
     * @return string
     */
    public function getProductAbstractNotSellableFacetName(): string
    {
        return CatalogConfig::PRODUCT_ABSTRACT_NOT_SELLABLE_FACET_NAME;
    }

    /**
     * @return string
     */
    public function getProductAbstractCustomerGroupFacetName(): string
    {
        return CatalogConfig::PRODUCT_ABSTRACT_CUSTOMER_GROUP_FACET_NAME;
    }

    /**
     * @return string
     */
    public function getProductAbstractNotCustomerGroupFacetName(): string
    {
        return CatalogConfig::PRODUCT_ABSTRACT_NOT_CUSTOMER_GROUP_FACET_NAME;
    }
}
