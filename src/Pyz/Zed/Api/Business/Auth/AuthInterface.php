<?php

namespace Pyz\Zed\Api\Business\Auth;

use Generated\Shared\Transfer\ApiRequestTransfer;

interface AuthInterface
{
    /**
     * Checks whether endpoint can be reached
     *
     * @param ApiRequestTransfer $apiRequestTransfer
     */
    public function checkAuth(ApiRequestTransfer $apiRequestTransfer): void;
}
