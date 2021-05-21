<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\Sso\Client;

use Generated\Shared\Transfer\SsoAccessTokenTransfer;

interface UserTokenInterface
{
    /**
     * @param string $code
     *
     * @return \Generated\Shared\Transfer\SsoAccessTokenTransfer
     */
    public function getAccessTokenByCode(string $code): SsoAccessTokenTransfer;

    /**
     * @param string $refreshToken
     *
     * @return \Generated\Shared\Transfer\SsoAccessTokenTransfer
     */
    public function getAccessTokenByRefreshToken(string $refreshToken): SsoAccessTokenTransfer;
}
