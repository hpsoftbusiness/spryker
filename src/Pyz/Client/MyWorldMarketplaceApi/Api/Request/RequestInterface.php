<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\MyWorldMarketplaceApi\Api\Request;

use Generated\Shared\Transfer\MyWorldMarketplaceApiResponseTransfer;

interface RequestInterface
{
    /**
     * @param string $url
     * @param array $requestParams
     * @param string $requestMethod
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function request(string $url, array $requestParams = [], string $requestMethod = 'POST'): MyWorldMarketplaceApiResponseTransfer;
}
