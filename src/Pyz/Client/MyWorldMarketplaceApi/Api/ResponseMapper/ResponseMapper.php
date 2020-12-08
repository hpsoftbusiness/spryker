<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\MyWorldMarketplaceApi\Api\ResponseMapper;

use Generated\Shared\Transfer\MyWorldMarketplaceApiResponseTransfer;

class ResponseMapper implements ResponseMapperInterface
{
    /**
     * @param array $response
     *
     * @return \Generated\Shared\Transfer\MyWorldMarketplaceApiResponseTransfer
     */
    public function map(array $response): MyWorldMarketplaceApiResponseTransfer
    {
        return (new MyWorldMarketplaceApiResponseTransfer())->fromArray($response, true);
    }
}
