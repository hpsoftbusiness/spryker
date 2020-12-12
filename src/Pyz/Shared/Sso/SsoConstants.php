<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Shared\Sso;

interface SsoConstants
{
    /**
     * Specification:
     * - SSO Token URL. Mandatory.
     */
    public const TOKEN_URL = 'SSO:TOKEN_URL';

    /**
     * Specification:
     * - Customer information endpoint URL. Mandatory.
     */
    public const CUSTOMER_INFORMATION_URL = 'SSO:CUSTOMER_INFORMATION_URL';

    /**
     * Specification:
     * - SSO Authorize URL. Mandatory.
     */
    public const AUTHORIZE_URL = 'SSO:AUTHORIZE_URL';

    /**
     * Specification:
     * - Login check path for SSO authenticator. Mandatory.
     */
    public const LOGIN_CHECK_PATH = 'SSO:LOGIN_CHECK_PATH';

    /**
     * Specification:
     * - Defined by oauth. Has to be "code". Mandatory.
     */
    public const RESPONSE_TYPE = 'SSO:RESPONSE_TYPE';

    /**
     * Specification:
     * -Client ID. Mandatory.
     */
    public const CLIENT_ID = 'SSO:CLIENT_ID';

    /**
     * Specification:
     * -Client Secret. Mandatory.
     */
    public const CLIENT_SECRET = 'SSO:CLIENT_SECRET';

    /**
     * Specification:
     * -User Agent (ApplicationName/Version). Mandatory.
     */
    public const USER_AGENT = 'SSO:USER_AGENT';

    /**
     * Specification:
     * - The URL to which the User Agent would be forwarded after login. Must be provided by consumer in advance. Mandatory.
     */
    public const REDIRECT_URL = 'SSO:REDIRECT_URL';

    /**
     * Specification:
     * - Requested Scopes, splitted with spaces " ". Mandatory.
     */
    public const SCOPE = 'SSO:SCOPE';

    /**
     * Specification:
     * - Enable/Disable SSO login.
     */
    public const SSO_LOGIN_ENABLED = 'CUSTOMER:SSO_LOGIN_ENABLED';
}
