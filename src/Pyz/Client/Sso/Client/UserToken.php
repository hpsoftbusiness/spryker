<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\Sso\Client;

use Generated\Shared\Transfer\SsoAccessTokenTransfer;
use GuzzleHttp\ClientInterface;
use Pyz\Client\Sso\SsoConfig;

class UserToken implements UserTokenInterface
{
    /**
     * @var \GuzzleHttp\Client
     */
    protected $httpClient;

    /**
     * @var \Pyz\Client\Sso\SsoConfig
     */
    protected $ssoConfig;

    public function __construct(ClientInterface $client, SsoConfig $ssoConfig)
    {
        $this->httpClient = $client;
        $this->ssoConfig = $ssoConfig;
    }

    /**
     * @param string $code
     *
     * @return \Generated\Shared\Transfer\SsoAccessTokenTransfer
     */
    public function getAccessTokenByCode(string $code): SsoAccessTokenTransfer
    {
        $accessTokenRequestParams = $this->getAccessTokenRequestParams($code);
        $result = $this->httpClient->request(
            'POST',
            $this->ssoConfig->getTokenUrl(),
            $accessTokenRequestParams
        );

        return (new SsoAccessTokenTransfer())->fromArray(json_decode($result->getBody()->getContents(), true));
    }

    /**
     * @param string $code
     *
     * @return array[]
     */
    protected function getAccessTokenRequestParams(string $code): array
    {
        return [
            'form_params' => [
                'grant_type' => 'authorization_code',
                'scope' => $this->ssoConfig->getScope(),
                'code' => $code,
                'redirect_uri' => $this->ssoConfig->getRedirectUrl(),
            ],
            'headers' => [
                'User-Agent' => $this->ssoConfig->getUserAgent(),
            ],
            'auth' => [
                $this->ssoConfig->getClientId(),
                $this->ssoConfig->getClientSecret(),
            ],
        ];
    }
}
