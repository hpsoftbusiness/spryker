<?php

namespace Pyz\Client\MyWorldMarketplaceApi;

use Generated\Shared\Transfer\AccessTokenTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Pyz\Client\MyWorldMarketplaceApi\MyWorldMarketplaceApiFactory getFactory()
 */
class MyWorldMarketplaceApiClient extends AbstractClient implements MyWorldMarketplaceApiClientInterface
{
    public function getAccessToken(): AccessTokenTransfer
    {
        return $this->getFactory()->createAccessToken()->getAccessToken();
    }
}
