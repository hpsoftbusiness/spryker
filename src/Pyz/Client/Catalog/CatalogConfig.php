<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\Catalog;

use Pyz\Shared\Catalog\CatalogConfig as SharedCatalogConfig;
use Spryker\Client\Catalog\CatalogConfig as SprykerCatalogConfig;

class CatalogConfig extends SprykerCatalogConfig
{
    public const PRODUCT_ABSTRACT_SELLABLE_FACET_NAME = SharedCatalogConfig::PRODUCT_ABSTRACT_SELLABLE_FACET_NAME;
    public const PRODUCT_ABSTRACT_NOT_SELLABLE_FACET_NAME =
        SharedCatalogConfig::PRODUCT_ABSTRACT_NOT_SELLABLE_FACET_NAME;

    public const PRODUCT_ABSTRACT_CUSTOMER_GROUP_FACET_NAME = SharedCatalogConfig::PRODUCT_ABSTRACT_CUSTOMER_GROUP_FACET_NAME;
    public const PRODUCT_ABSTRACT_NOT_CUSTOMER_GROUP_FACET_NAME = SharedCatalogConfig::PRODUCT_ABSTRACT_NOT_CUSTOMER_GROUP_FACET_NAME;

    public const PRODUCT_ABSTRACT_DEAL_FACET_NAME = SharedCatalogConfig::PRODUCT_ABSTRACT_DEAL_FACET_NAME;

    protected const PAGINATION_CATALOG_SEARCH_DEFAULT_ITEMS_PER_PAGE = 40;

    protected const PAGINATION_VALID_ITEMS_PER_PAGE = [
        10,
        100,
        1000,
    ];

    protected const PAGINATION_CATALOG_SEARCH_VALID_ITEMS_PER_PAGE = [
        12, 24, 36, 100,
    ];
}
