<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\Sso;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\SsoAccessTokenTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Pyz\Client\Sso\SsoFactory getFactory()
 */
class SsoClient extends AbstractClient implements SsoClientInterface
{
    /**
     * @param string $code
     *
     * @return \Generated\Shared\Transfer\SsoAccessTokenTransfer
     */
    public function getAccessTokenByCode(string $code): SsoAccessTokenTransfer
    {
        return $this->getFactory()->createSsoStub()->getAccessTokenByCode($code);
    }

    /**
     * @param \Generated\Shared\Transfer\SsoAccessTokenTransfer $ssoAccessTokenTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer|null
     */
    public function getCustomerInformationBySsoAccessToken(SsoAccessTokenTransfer $ssoAccessTokenTransfer): ?CustomerTransfer
    {
        return $this->getFactory()->createCustomerInformationClient()->getCustomerInformationBySsoAccessToken($ssoAccessTokenTransfer);
    }

    /**
     * @return string
     */
    public function getLoginCheckPath(): string
    {
        return $this->getFactory()->createConfigReader()->getLoginCheckPath();
    }

    /**
     * @param string $locale
     *
     * @return string
     */
    public function getAuthorizeUrl(string $locale): string
    {
        return $this->getFactory()->createConfigReader()->getAuthorizeUrl($locale);
    }

    /**
     * @return bool
     */
    public function isSsoLoginEnabled(): bool
    {
        return $this->getFactory()->createConfigReader()->isSsoLoginEnabled();
    }
}
