<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\Sso\Config;

use Pyz\Client\Sso\SsoConfig;

class ConfigReader implements ConfigReaderInterface
{
    /**
     * @var \Pyz\Client\Sso\SsoConfig
     */
    protected $ssoConfig;

    /**
     * @param \Pyz\Client\Sso\SsoConfig $ssoConfig
     */
    public function __construct(SsoConfig $ssoConfig)
    {
        $this->ssoConfig = $ssoConfig;
    }

    /**
     * @return string
     */
    public function getLoginCheckPath(): string
    {
        return $this->ssoConfig->getLoginCheckPath();
    }

    /**
     * @param string $locale
     *
     * @return string
     */
    public function getAuthorizeUrl(string $locale): string
    {
        $httpQuery = http_build_query(
            [
                'response_type' => $this->ssoConfig->getResponseType(),
                'client_id' => $this->ssoConfig->getClientId(),
                'redirect_uri' => $this->ssoConfig->getRedirectUrl(),
                'scope' => $this->ssoConfig->getScope(),
                'lang' => str_replace('_', '-', $locale),
            ]
        );

        return sprintf('%s?%s', $this->ssoConfig->getAuthorizeUrl(), $httpQuery);
    }
}
