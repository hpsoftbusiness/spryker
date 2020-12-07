<?php

namespace Pyz\Client\MyWorldMarketplaceApi;

use Generated\Shared\Transfer\AccessTokenTransfer;
use Generated\Shared\Transfer\MyWorldMarketplaceApiResponseTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Pyz\Client\MyWorldMarketplaceApi\MyWorldMarketplaceApiFactory getFactory()
 */
class MyWorldMarketplaceApiClient extends AbstractClient implements MyWorldMarketplaceApiClientInterface
{
    /**
     * @return \Generated\Shared\Transfer\AccessTokenTransfer
     */
    public function getAccessToken(): AccessTokenTransfer
    {
        return $this->getFactory()->createAccessToken()->getAccessToken();
    }

    /**
     * @param string $url
     * @param array $requestParams
     * @param string $requestMethod
     *
     * @return \Generated\Shared\Transfer\MyWorldMarketplaceApiResponseTransfer
     */
    public function performApiRequest(string $url, array $requestParams = [], string $requestMethod = 'POST'): MyWorldMarketplaceApiResponseTransfer
    {
        return $this->getFactory()->createRequest()->request($url, $requestParams, $requestMethod);
    }
}
