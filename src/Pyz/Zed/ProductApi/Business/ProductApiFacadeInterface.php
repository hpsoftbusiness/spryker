<?php

namespace Pyz\Zed\ProductApi\Business;

use Generated\Shared\Transfer\ApiItemTransfer;
use Generated\Shared\Transfer\ApiRequestTransfer;

interface ProductApiFacadeInterface
{
    /**
     * @param ApiRequestTransfer $apiRequestTransfer
     *
     * @return ApiItemTransfer
     */
    public function findProducts(ApiRequestTransfer $apiRequestTransfer): ApiItemTransfer;

    /**
     * @param string $authType
     * @param ApiRequestTransfer $apiRequestTransfer
     */
    public function checkAuth(string $authType, ApiRequestTransfer $apiRequestTransfer): void;

    /**
     * @param ApiRequestTransfer $apiRequestTransfer
     */
    public function validateLanguage(ApiRequestTransfer $apiRequestTransfer): void;
}
