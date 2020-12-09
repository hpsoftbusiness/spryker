<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Service\ProductAffiliate;

use Spryker\Service\Kernel\AbstractBundleConfig;

class ProductAffiliateConfig extends AbstractBundleConfig
{
    protected const TRACKING_URL_PATH = 'https://click.myworld.com/spryker';
    protected const TRACKING_URL_NETWORK_ARGUMENT = 'AW_Dach';

    /**
     * @api
     *
     * @return string
     */
    public function getTrackingUrlPath(): string
    {
        return static::TRACKING_URL_PATH;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getTrackingUrlNetworkArgument(): string
    {
        return static::TRACKING_URL_NETWORK_ARGUMENT;
    }
}
