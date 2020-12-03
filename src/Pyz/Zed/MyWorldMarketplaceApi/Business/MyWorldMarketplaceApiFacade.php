<?php

namespace Pyz\Zed\MyWorldMarketplaceApi\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Pyz\Zed\MyWorldMarketplaceApi\Business\MyWorldMarketplaceApiBusinessFactory getFactory()
 */
class MyWorldMarketplaceApiFacade extends AbstractFacade implements MyWorldMarketplaceApiFacadeInterface
{

    public function test()
    {
        return $this->getFactory()->getMyWorldMarketplaceApiClient()->getAccessToken();
    }

}
