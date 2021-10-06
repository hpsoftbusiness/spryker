<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductApi\Communication\Plugin\Api;

use Pyz\Zed\ProductApi\ProductApiConfig;

/**
 * @deprecated Please use Glue API instead (Pyz/Glue/ProductFeedRestApi)
 */
class SpDealsProductApiResourcePlugin extends AbstractProductApiResourcePlugin
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getResourceName()
    {
        return ProductApiConfig::RESOURCE_SPDEALS;
    }
}
