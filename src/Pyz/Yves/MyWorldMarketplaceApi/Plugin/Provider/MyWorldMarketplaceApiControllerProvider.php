<?php

namespace Pyz\Yves\MyWorldMarketplaceApi\Plugin\Provider;

use SprykerShop\Yves\ShopApplication\Plugin\Provider\AbstractYvesControllerProvider;
use Silex\Application;

class MyWorldMarketplaceApiControllerProvider extends AbstractYvesControllerProvider
{

    const MYWORLDMARKETPLACEAPI_INDEX = 'myworldmarketplaceapi-index';

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
