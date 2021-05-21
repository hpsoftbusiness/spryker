<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\Sso;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\SsoAccessTokenTransfer;

interface SsoClientInterface
{
    /**
     * @param string $code
     *
     * @return \Generated\Shared\Transfer\SsoAccessTokenTransfer
     */
    public function getAccessTokenByCode(string $code): SsoAccessTokenTransfer;

    /**
     * @param \Generated\Shared\Transfer\SsoAccessTokenTransfer $ssoAccessTokenTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer|null
     */
    public function getCustomerInformationBySsoAccessToken(SsoAccessTokenTransfer $ssoAccessTokenTransfer): ?CustomerTransfer;

    /**
     * @return string
     */
    public function getLoginCheckPath(): string;

    /**
     * @param string $locale
     * @param string|null $state
     *
     * @return string
     */
    public function getAuthorizeUrl(string $locale, ?string $state = null): string;

    /**
     * @return bool
     */
    public function isSsoLoginEnabled(): bool;

    /**
     * @param string $refreshToken
     *
     * @return \Generated\Shared\Transfer\SsoAccessTokenTransfer
     */
    public function getAccessTokenByRefreshToken(string $refreshToken): SsoAccessTokenTransfer;
}
