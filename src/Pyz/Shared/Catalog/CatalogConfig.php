<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Shared\Catalog;

use Spryker\Shared\Kernel\AbstractBundleConfig;

class CatalogConfig extends AbstractBundleConfig
{
    public const PRODUCT_ABSTRACT_SELLABLE_FACET_NAME = 'sellable';
    public const PRODUCT_ABSTRACT_NOT_SELLABLE_FACET_NAME = 'not-sellable';
    public const PRODUCT_ABSTRACT_CUSTOMER_GROUP_FACET_NAME = 'customer-groups';
    public const PRODUCT_ABSTRACT_NOT_CUSTOMER_GROUP_FACET_NAME = 'not-customer-groups';
    public const PRODUCT_ABSTRACT_DEAL_FACET_NAME = 'deal';
}
