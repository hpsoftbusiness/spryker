<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Api\Business\Auth;

use Generated\Shared\Transfer\ApiRequestTransfer;

interface AuthInterface
{
    /**
     * Checks whether endpoint can be reached
     *
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $apiRequestTransfer
     *
     * @return void
     */
    public function checkAuth(ApiRequestTransfer $apiRequestTransfer): void;
}
