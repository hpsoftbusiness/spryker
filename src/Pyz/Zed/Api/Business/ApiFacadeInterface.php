<?php

namespace Pyz\Zed\Api\Business;

use Generated\Shared\Transfer\ApiRequestTransfer;
use Spryker\Zed\Api\Business\ApiFacadeInterface as SprykerApiFacadeInterface;

interface ApiFacadeInterface extends SprykerApiFacadeInterface
{
    /**
     * @param string $authType
     * @param ApiRequestTransfer $apiRequestTransfer
     */
    public function checkAuth(string $authType, ApiRequestTransfer $apiRequestTransfer): void;
}
