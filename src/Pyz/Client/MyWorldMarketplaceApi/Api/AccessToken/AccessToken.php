<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\MyWorldMarketplaceApi\Api\AccessToken;

use Exception;
use Generated\Shared\Transfer\AccessTokenTransfer;
use GuzzleHttp\ClientInterface;
use Pyz\Client\MyWorldMarketplaceApi\Exception\MyWorldMarketplaceApiAccessTokenException;
use Pyz\Client\MyWorldMarketplaceApi\MyWorldMarketplaceApiConfig;
use Spryker\Client\Session\SessionClientInterface;
use Spryker\Service\UtilEncoding\UtilEncodingServiceInterface;
use Spryker\Shared\ErrorHandler\ErrorHandler;
use Spryker\Shared\ErrorHandler\ErrorLoggerInterface;

class AccessToken implements AccessTokenInterface
{
    /**
     * @var \GuzzleHttp\ClientInterface
     */
    protected $httpClient;

    /**
     * @var \Pyz\Client\MyWorldMarketplaceApi\MyWorldMarketplaceApiConfig
     */
    protected $myWorldMarketplaceApiConfig;

    /**
     * @var \Spryker\Service\UtilEncoding\UtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @var \Spryker\Shared\ErrorHandler\ErrorLoggerInterface
     */
    protected $errorLogger;

    /**
     * @param \GuzzleHttp\ClientInterface $httpClient
     * @param \Pyz\Client\MyWorldMarketplaceApi\MyWorldMarketplaceApiConfig $myWorldMarketplaceApiConfig
     * @param \Spryker\Service\UtilEncoding\UtilEncodingServiceInterface $utilEncodingService
     * @param \Spryker\Shared\ErrorHandler\ErrorLoggerInterface $errorLogger
     */
    public function __construct(
        ClientInterface $httpClient,
        MyWorldMarketplaceApiConfig $myWorldMarketplaceApiConfig,
        UtilEncodingServiceInterface $utilEncodingService,
        ErrorLoggerInterface $errorLogger
    )
    {
        $this->httpClient = $httpClient;
        $this->myWorldMarketplaceApiConfig = $myWorldMarketplaceApiConfig;
        $this->utilEncodingService = $utilEncodingService;
        $this->errorLogger = $errorLogger;
    }


    /**
     * @throws \Pyz\Client\MyWorldMarketplaceApi\Exception\MyWorldMarketplaceApiAccessTokenException
     *
     * @return \Generated\Shared\Transfer\AccessTokenTransfer
     */
    public function getAccessToken(): AccessTokenTransfer
    {
        $requestParams = $this->getRequestParams();

        try {
            $response = $this->httpClient->request(
                'POST',
                $this->myWorldMarketplaceApiConfig->getTokenUrl(),
                $requestParams
            );

            $response = $this->utilEncodingService->decodeJson($response->getBody()->getContents(), true);
            $this->validateResponse($response);

            return (new AccessTokenTransfer())
                ->setAccessToken($response['access_token'])
                ->setTokenType($response['token_type'] ?? null)
                ->setExpiresIn($response['expires_in'] ?? null)
                ->setRefreshToken($response['refresh_token'] ?? null);

        } catch (Exception $e) {
            $this->errorLogger->log($e);
        }

        throw new MyWorldMarketplaceApiAccessTokenException();
    }

    /**
     * @return array
     */
    protected function getRequestParams(): array
    {
        return [
            'form_params' => [
                'grant_type' => 'client_credentials',
                'scope' => 'apitest',
            ],
            'headers' => [
                'User-Agent' => $this->myWorldMarketplaceApiConfig->getUserAgent(),
            ],
            'auth' => [
                $this->myWorldMarketplaceApiConfig->getClientId(),
                $this->myWorldMarketplaceApiConfig->getClientSecret(),
            ],
        ];
    }

    /**
     * @param array $response
     *
     * @throws \Pyz\Client\MyWorldMarketplaceApi\Exception\MyWorldMarketplaceApiAccessTokenException
     */
    protected function validateResponse(array $response): void
    {
        if (empty($response['access_token'])) {
            throw new MyWorldMarketplaceApiAccessTokenException();
        }
    }
}
