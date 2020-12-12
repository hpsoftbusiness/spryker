<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\Sso;

use Spryker\Client\Kernel\AbstractBundleConfig;

/**
 * @method \Pyz\Shared\Sso\SsoConfig getSharedConfig()
 */
class SsoConfig extends AbstractBundleConfig
{
    /**
     * @return string
     */
    public function getAuthorizeUrl(): string
    {
        return $this->getSharedConfig()->getAuthorizeUrl();
    }

    /**
     * @return string
     */
    public function getLoginCheckPath(): string
    {
        return $this->getSharedConfig()->getLoginCheckPath();
    }

    /**
     * @return string
     */
    public function getTokenUrl(): string
    {
        return $this->getSharedConfig()->getTokenUrl();
    }

    /**
     * @return string
     */
    public function getCustomerInformationUrl(): string
    {
        return $this->getSharedConfig()->getCustomerInformationUrl();
    }

    /**
     * @return string
     */
    public function getResponseType(): string
    {
        return $this->getSharedConfig()->getResponseType();
    }

    /**
     * @return string
     */
    public function getClientId(): string
    {
        return $this->getSharedConfig()->getClientId();
    }

    /**
     * @return string
     */
    public function getClientSecret(): string
    {
        return $this->getSharedConfig()->getClientSecret();
    }

    /**
     * @return string
     */
    public function getUserAgent(): string
    {
        return $this->getSharedConfig()->getUserAgent();
    }

    /**
     * @return string
     */
    public function getRedirectUrl(): string
    {
        return $this->getSharedConfig()->getRedirectUrl();
    }

    /**
     * @return string
     */
    public function getScope(): string
    {
        return $this->getSharedConfig()->getScope();
    }

    /**
     * @return bool
     */
    public function isSsoLoginEnabled(): bool
    {
        return $this->getSharedConfig()->isSsoLoginEnabled();
    }
}
