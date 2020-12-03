<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Sso\Business\AccessToken;


use Exception;
use Generated\Shared\Transfer\SsoAccessTokenTransfer;
use GuzzleHttp\ClientInterface;
use Pyz\Zed\Sso\SsoConfig;
use Spryker\Shared\ErrorHandler\ErrorLoggerInterface;

class AccessToken implements AccessTokenInterface
{
    /**
     * @var \GuzzleHttp\ClientInterface
     */
    protected $httpClient;
    /**
     * @var \Pyz\Zed\Sso\SsoConfig
     */
    protected $ssoConfig;
    /**
     * @var \Spryker\Shared\ErrorHandler\ErrorLoggerInterface
     */
    protected $errorLogger;

    public function __construct(
        ClientInterface $httpClient,
        SsoConfig $ssoConfig,
        ErrorLoggerInterface $errorLogger
    )
    {
        $this->httpClient = $httpClient;
        $this->ssoConfig = $ssoConfig;
        $this->errorLogger = $errorLogger;
    }

    /**
     * @param string $code
     *
     * @return \Generated\Shared\Transfer\SsoAccessTokenTransfer
     */
    public function getAccessTokenByCode(string $code): SsoAccessTokenTransfer
    {
        $accessTokenRequestParams = $this->getAccessTokenRequestParams($code);
        try {
            $result = $this->httpClient->request(
                'POST',
                $this->ssoConfig->getTokenUrl(),
                $accessTokenRequestParams
            );
        } catch (Exception $e) {
            $this->errorLogger->log($e);

            return new SsoAccessTokenTransfer();
        }

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
