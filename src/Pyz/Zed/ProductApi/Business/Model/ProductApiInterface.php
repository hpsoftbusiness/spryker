<?php

namespace Pyz\Zed\ProductApi\Business\Model;

use Generated\Shared\Transfer\ApiItemTransfer;
use Generated\Shared\Transfer\ApiRequestTransfer;

interface ProductApiInterface
{
    /**
     * @param ApiRequestTransfer $apiRequestTransfer
     *
     * @return ApiItemTransfer
     */
    public function find(ApiRequestTransfer $apiRequestTransfer): ApiItemTransfer;
}
