<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\MyWorldMarketplaceApi\Plugin\Provider;

use Silex\Application;
use SprykerShop\Yves\ShopApplication\Plugin\Provider\AbstractYvesControllerProvider;

class MyWorldMarketplaceApiControllerProvider extends AbstractYvesControllerProvider
{
    public const MYWORLDMARKETPLACEAPI_INDEX = 'myworldmarketplaceapi-index';

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    protected function defineControllers(Application $app)
    {
        $this->createGetController('/my-world-marketplace-api', static::MYWORLDMARKETPLACEAPI_INDEX, 'MyWorldMarketplaceApi', 'Index', 'index');
    }
}
