<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\MyWorldMarketplaceApi;

use Generated\Shared\Transfer\AccessTokenTransfer;
use Generated\Shared\Transfer\MyWorldMarketplaceApiResponseTransfer;

interface MyWorldMarketplaceApiClientInterface
{
    /**
     * @return \Generated\Shared\Transfer\AccessTokenTransfer
     */
    public function getAccessToken(): AccessTokenTransfer;

    /**
     * @param string $url
     * @param array $requestParams
     * @param string $requestMethod
     *
     * @return \Generated\Shared\Transfer\MyWorldMarketplaceApiResponseTransfer
     */
    public function performApiRequest(string $url, array $requestParams = [], string $requestMethod = 'POST'): MyWorldMarketplaceApiResponseTransfer;
}
