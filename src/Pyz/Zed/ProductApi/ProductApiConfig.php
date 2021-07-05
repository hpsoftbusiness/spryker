<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductApi;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class ProductApiConfig extends AbstractBundleConfig
{
    public const RESOURCE_PRODUCTS = 'products';
    public const RESOURCE_BVDEALS = 'bvdeals';
    public const RESOURCE_SPDEALS = 'spdeals';
    public const RESOURCE_ELITE_CLUB = 'elite-club';
    public const RESOURCE_ONE_SENSE = 'one-sense';
    public const RESOURCE_LYCONET = 'lyconet';
    public const RESOURCE_FEATURED_PRODUCTS = 'featured-products';
    public const RESOURCE_ELITE_CLUB_EC_DEAL_ONLY = 'elite-club-ec-deal-only';
}
