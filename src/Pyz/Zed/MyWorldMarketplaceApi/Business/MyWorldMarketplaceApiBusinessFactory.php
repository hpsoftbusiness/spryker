<?php

namespace Pyz\Zed\MyWorldMarketplaceApi\Business;

use Pyz\Client\MyWorldMarketplaceApi\MyWorldMarketplaceApiClientInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Pyz\Zed\MyWorldMarketplaceApi\MyWorldMarketplaceApiConfig getConfig()
 * @method \Pyz\Zed\MyWorldMarketplaceApi\Persistence\MyWorldMarketplaceApiQueryContainer getQueryContainer()
 */
class MyWorldMarketplaceApiBusinessFactory extends AbstractBusinessFactory
{

    public function getMyWorldMarketplaceApiClient(): MyWorldMarketplaceApiClientInterface
    {
        return $this->getProvidedDependency('CLIENT');
    }

}
