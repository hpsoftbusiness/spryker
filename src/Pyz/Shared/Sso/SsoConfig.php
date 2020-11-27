<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Shared\Sso;

use Spryker\Shared\Kernel\AbstractBundleConfig;

class SsoConfig extends AbstractBundleConfig
{
    /**
     * @return string
     */
    public function getAuthorizeUrl(): string
    {
        return $this->get(SsoConstants::AUTHORIZE_URL);
    }

    /**
     * @return string
     */
    public function getLoginCheckPath(): string
    {
        return $this->get(SsoConstants::LOGIN_CHECK_PATH);
    }

    /**
     * @return string
     */
    public function getTokenUrl(): string
    {
        return $this->get(SsoConstants::TOKEN_URL);
    }

    /**
     * @return string
     */
    public function getCustomerInformationUrl(): string
    {
        return $this->get(SsoConstants::CUSTOMER_INFORMATION_URL);
    }

    /**
     * @return string
     */
    public function getResponseType(): string
    {
        return $this->get(SsoConstants::RESPONSE_TYPE);
    }

    /**
     * @return string
     */
    public function getClientId(): string
    {
        return $this->get(SsoConstants::CLIENT_ID);
    }

    /**
     * @return string
     */
    public function getClientSecret(): string
    {
        return $this->get(SsoConstants::CLIENT_SECRET);
    }

    /**
     * @return string
     */
    public function getUserAgent(): string
    {
        return $this->get(SsoConstants::USER_AGENT);
    }

    /**
     * @return string
     */
    public function getRedirectUrl(): string
    {
        return $this->get(SsoConstants::REDIRECT_URL);
    }

    /**
     * @return string
     */
    public function getScope(): string
    {
        return $this->get(SsoConstants::SCOPE);
    }
}
