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
     * Get access token by code
     *
     * @api
     *
     * @param string $code
     *
     * @return \Generated\Shared\Transfer\SsoAccessTokenTransfer
     */
    public function getAccessTokenByCode(string $code): SsoAccessTokenTransfer;

    /**
     * Get customer information by SSO access token
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SsoAccessTokenTransfer $ssoAccessTokenTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer|null
     */
    public function getCustomerInformationBySsoAccessToken(
        SsoAccessTokenTransfer $ssoAccessTokenTransfer
    ): ?CustomerTransfer;

    /**
     * Get login check path
     *
     * @api
     *
     * @return string
     */
    public function getLoginCheckPath(): string;

    /**
     * Get Authorize url
     *
     * @api
     *
     * @param string $locale
     * @param string|null $state
     *
     * @return string
     */
    public function getAuthorizeUrl(string $locale, ?string $state = null): string;

    /**
     * Is SSO login enable
     *
     * @api
     *
     * @return bool
     */
    public function isSsoLoginEnabled(): bool;

    /**
     * Get access token by refresh token
     *
     * @api
     *
     * @param string $refreshToken
     *
     * @return \Generated\Shared\Transfer\SsoAccessTokenTransfer
     */
    public function getAccessTokenByRefreshToken(string $refreshToken): SsoAccessTokenTransfer;
}
